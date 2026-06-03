// =====================================================
// GLOBAL AJAX HELPER
// =====================================================

async function apiFetch(url, method = "GET", data = null){

    let options = {

        method: method,

        headers: {
            "Content-Type": "application/json"
        }
    };

    if(data){

        options.body = JSON.stringify(data);
    }

    try{

        const response = await fetch(url, options);

        return await response.json();

    }catch(error){

        console.error(error);

        return {

            success: false,
            message: "Server Error"
        };
    }
}

// =====================================================
// DELETE TOPIC AJAX
// =====================================================

async function deleteTopic(id){

    if(!confirm("Delete this topic?")){

        return;
    }

    const result = await apiFetch(

        `index.php?page=api-topic-delete&id=${id}`,
        "DELETE"
    );

    if(result.success){

        const row = document.getElementById("row-" + id);

        if(row){

            row.remove();
        }

        alert(result.message);

    }else{

        alert(result.message);
    }
}

// =====================================================
// AJAX CREATE TOPIC
// =====================================================

const topicForm = document.getElementById("topicForm");

if(topicForm){

    topicForm.addEventListener("submit", async function(e){

        e.preventDefault();

        const formData = new FormData(topicForm);

        const data = Object.fromEntries(
            formData.entries()
        );

        const result = await apiFetch(

            "index.php?page=api-topic-store",
            "POST",
            data
        );

        if(result.success){

            alert("Topic created successfully");

            location.reload();

        }else{

            alert(result.message || "Create failed");
        }
    });
}

// =====================================================
// AJAX UPDATE TOPIC
// =====================================================

const editTopicForm = document.getElementById("editTopicForm");

if(editTopicForm){

    editTopicForm.addEventListener("submit", async function(e){

        e.preventDefault();

        const formData = new FormData(editTopicForm);

        const data = Object.fromEntries(
            formData.entries()
        );

        const id = data.id;

        const result = await apiFetch(

            `index.php?page=api-topic-update&id=${id}`,
            "POST",
            data
        );

        if(result.success){

            alert("Topic updated");

            location.reload();

        }else{

            alert(result.message || "Update failed");
        }
    });
}

// =====================================================
// AJAX SEARCH
// =====================================================

const searchInput = document.getElementById("searchInput");

if(searchInput){

    searchInput.addEventListener("keyup", function(){

        let keyword = this.value.toLowerCase();

        let rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {

            let text = row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword)
                ? ""
                : "none";
        });
    });
}

// =====================================================
// DATATABLES SAFE INIT
// =====================================================

$(document).ready(function(){

    if($('.datatable').length){

        $('.datatable').each(function(){

            const columnCount =
                $(this).find('thead th').length;

            const bodyCount =
                $(this)
                .find('tbody tr:first td')
                .length;

            if(columnCount === bodyCount){

                $(this).DataTable({

                    pageLength: 5,
                    responsive: true

                });

            }else{

                console.warn(
                    "DataTable column mismatch"
                );
            }
        });
    }
});

// =====================================================
// AJAX DASHBOARD STATS
// =====================================================

const topicCountElement =
document.getElementById("topic-count");

if(topicCountElement){

    setInterval(async () => {

        try{

            const response = await fetch(
                "index.php?page=dashboard-stats"
            );

            const data = await response.json();

            topicCountElement.innerText =
                data.topics ?? 0;

        }catch(error){

            console.log(error);
        }

    }, 5000);
}

// =====================================================
// PREVIEW IMAGE UPLOAD
// =====================================================

const imageInput =
document.getElementById("imageInput");

if(imageInput){

    imageInput.addEventListener("change", function(e){

        const file = e.target.files[0];

        if(!file){
            return;
        }

        const reader = new FileReader();

        reader.onload = function(event){

            const preview =
            document.getElementById("previewImage");

            if(preview){

                preview.src = event.target.result;

                preview.style.display = "block";
            }
        };

        reader.readAsDataURL(file);
    });
}

// =====================================================
// LOADER
// =====================================================

function showLoader(){

    const loader =
    document.getElementById("loader");

    if(loader){

        loader.style.display = "block";
    }
}

function hideLoader(){

    const loader =
    document.getElementById("loader");

    if(loader){

        loader.style.display = "none";
    }
}