<?php
class AssessmentMapper extends Mapper
{
    public function getAssessments() {
        $sql = "SELECT a.id, a.name, a.description
            from assessment a";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new AssessmentEntity($row);
        }
        return $results;
    }
    /**
     * Get one Assessment by its ID
     *
     * @param int $assessment_id The ID of the assessment
     * @return AssessmentEntity  The assessment
     */
    public function getAssessmentById($assessment_id) {
        $sql = "SELECT a.id, a.name, a.description
            from assessment a
            where a.id = :assessment_id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["assessment_id" => $assessment_id]);
        if($result) {
            return new AssessmentEntity($stmt->fetch());
        }
    }
    public function save(AssessmentEntity $assessment) {
        $sql = "insert into assessment
            (name, description) values
            (:name, :description)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "name" => $assessment->getName(),
            "description" => $assessment->getDescription(),
        ]);
        if(!$result) {
            throw new Exception("could not save record");
        }
    }
}