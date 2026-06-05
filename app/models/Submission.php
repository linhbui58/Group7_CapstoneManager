<?php

class Submission {

    private $conn;

    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll(){
        $sql = "SELECT submissions.*,
                       students.full_name AS student_name,
                       milestones.title   AS milestone_title
                FROM submissions
                JOIN students   ON students.id   = submissions.student_id
                JOIN milestones ON milestones.id  = submissions.milestone_id
                ORDER BY submissions.id DESC";
        try {
            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function find($id){
        $stmt = $this->conn->prepare(
            "SELECT submissions.*,
                    students.full_name AS student_name,
                    milestones.title   AS milestone_title
             FROM submissions
             JOIN students   ON students.id   = submissions.student_id
             JOIN milestones ON milestones.id  = submissions.milestone_id
             WHERE submissions.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data){
        // submissions.topic_id có thể null nếu không truyền
        $sql = "INSERT INTO submissions
                (student_id, topic_id, milestone_id, file_path, status, submitted_at)
                VALUES(?, ?, ?, ?, 'submitted', NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['student_id'],
            $data['topic_id']    ?? null,
            $data['milestone_id'],
            $data['file_path'],
        ]);
    }

    public function updateStatus($id, $status){
        // Map status từ UI sang enum DB
        $map = [
            'reviewed' => 'submitted',   // tạm dùng submitted = đã xem
            'rejected' => 'revision_required',
            'submitted'=> 'submitted',
            'late'     => 'late',
            'revision_required' => 'revision_required',
        ];
        $dbStatus = $map[$status] ?? 'submitted';

        $stmt = $this->conn->prepare(
            "UPDATE submissions SET status = ? WHERE id = ?"
        );
        return $stmt->execute([$dbStatus, $id]);
    }

    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM submissions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByStudent($studentId){
        $stmt = $this->conn->prepare(
            "SELECT submissions.*,
                    students.full_name AS student_name,
                    milestones.title   AS milestone_title
             FROM submissions
             JOIN students   ON students.id   = submissions.student_id
             JOIN milestones ON milestones.id  = submissions.milestone_id
             WHERE submissions.student_id = ?
             ORDER BY submissions.submitted_at DESC"
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lecturer thấy submissions của sinh viên thuộc đề tài mình phụ trách.
     * Dùng submissions.topic_id (cột thực tế trong DB) join topic_assignments.
     * Fallback: nếu topic_id null thì dùng student_id qua topic_registrations.
     */
    public function getByLecturer($lecturerId){
        $sql = "SELECT DISTINCT submissions.*,
                       students.full_name AS student_name,
                       milestones.title   AS milestone_title
                FROM submissions
                JOIN students   ON students.id   = submissions.student_id
                JOIN milestones ON milestones.id  = submissions.milestone_id
                WHERE (
                    -- Cách 1: submissions.topic_id trực tiếp join topic_assignments
                    (submissions.topic_id IS NOT NULL AND submissions.topic_id IN (
                        SELECT topic_id FROM topic_assignments WHERE lecturer_id = ?
                    ))
                    OR
                    -- Cách 2: qua topic_registrations.desired_lecturer_id
                    submissions.student_id IN (
                        SELECT student_id FROM topic_registrations
                        WHERE desired_lecturer_id = ?
                    )
                    OR
                    -- Cách 3: student đăng ký topic được assign cho lecturer
                    submissions.student_id IN (
                        SELECT tr.student_id FROM topic_registrations tr
                        JOIN topic_assignments ta ON ta.topic_id = tr.topic_id
                        WHERE ta.lecturer_id = ?
                    )
                )
                ORDER BY submissions.submitted_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lecturerId, $lecturerId, $lecturerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra submission có thuộc phạm vi của lecturer không.
     */
    public function belongsToLecturer($submissionId, $lecturerId){
        $sql = "SELECT COUNT(*) FROM submissions sub
                WHERE sub.id = ?
                  AND (
                      (sub.topic_id IS NOT NULL AND sub.topic_id IN (
                          SELECT topic_id FROM topic_assignments WHERE lecturer_id = ?
                      ))
                      OR sub.student_id IN (
                          SELECT student_id FROM topic_registrations
                          WHERE desired_lecturer_id = ?
                      )
                      OR sub.student_id IN (
                          SELECT tr.student_id FROM topic_registrations tr
                          JOIN topic_assignments ta ON ta.topic_id = tr.topic_id
                          WHERE ta.lecturer_id = ?
                      )
                  )";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$submissionId, $lecturerId, $lecturerId, $lecturerId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
