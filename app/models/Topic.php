<?php
class Topic {
    private $conn;

    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll(){
        $sql = "SELECT topics.*, semesters.name AS semester
                FROM topics
                JOIN semesters ON semesters.id = topics.semester_id
                ORDER BY topics.id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Chỉ lấy topic có status = 'approved' (dùng cho student đăng ký)
    public function getAvailable(){
        $sql = "SELECT topics.*, semesters.name AS semester
                FROM topics
                JOIN semesters ON semesters.id = topics.semester_id
                WHERE topics.status = 'approved'
                ORDER BY topics.id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id){
        $stmt = $this->conn->prepare("SELECT * FROM topics WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data){
        $stmt = $this->conn->prepare(
            "INSERT INTO topics (title, description, keywords, semester_id, created_by, status) 
             VALUES(?,?,?,?,?,?)"
        );
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['keywords'],
            $data['semester_id'],
            $data['created_by'],
            $data['status'] ?? 'pending',
        ]);
    }

    public function update($id, $data){
        $stmt = $this->conn->prepare(
            "UPDATE topics SET title=?, description=?, keywords=?, semester_id=?, status=? WHERE id=?"
        );
        return $stmt->execute([
            $data['title'], $data['description'], $data['keywords'], 
            $data['semester_id'], $data['status'], $id
        ]);
    }

    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM topics WHERE id=?");
        return $stmt->execute([$id]);
    }

    // Kiểm tra trùng tên trong cùng học kỳ (excludeId dùng khi update)
    public function existsInSemester($title, $semesterId, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare(
                "SELECT id FROM topics WHERE title = ? AND semester_id = ? AND id != ?"
            );
            $stmt->execute([$title, $semesterId, $excludeId]);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT id FROM topics WHERE title = ? AND semester_id = ?"
            );
            $stmt->execute([$title, $semesterId]);
        }
        return $stmt->fetch() ? true : false;
    }

    // Cập nhật status (admin / lecturer)
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE topics SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Search + filter (dùng cho index)
    public function search($keyword = '', $semesterId = 0, $status = '') {
        $sql = "SELECT topics.*, semesters.name AS semester
                FROM topics
                JOIN semesters ON semesters.id = topics.semester_id
                WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (topics.title LIKE ? OR topics.keywords LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        if ($semesterId > 0) {
            $sql .= " AND topics.semester_id = ?";
            $params[] = $semesterId;
        }
        if ($status !== '') {
            $sql .= " AND topics.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY topics.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lecturer: xem đề tài được assign cho mình + đề tài sinh viên đăng ký chọn mình
    public function getByLecturer($lecturerId, $keyword = '', $semesterId = 0, $status = '') {
        $sql = "SELECT DISTINCT topics.*, semesters.name AS semester
                FROM topics
                JOIN semesters ON semesters.id = topics.semester_id
                WHERE (
                    -- Đề tài được admin assign trực tiếp
                    topics.id IN (
                        SELECT topic_id FROM topic_assignments WHERE lecturer_id = ?
                    )
                    OR
                    -- Đề tài sinh viên đăng ký và chọn lecturer này
                    topics.id IN (
                        SELECT topic_id FROM topic_registrations WHERE desired_lecturer_id = ?
                    )
                )";
        $params = [$lecturerId, $lecturerId];

        if ($keyword !== '') {
            $sql .= " AND (topics.title LIKE ? OR topics.keywords LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        if ($semesterId > 0) {
            $sql .= " AND topics.semester_id = ?";
            $params[] = $semesterId;
        }
        if ($status !== '') {
            $sql .= " AND topics.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY topics.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}