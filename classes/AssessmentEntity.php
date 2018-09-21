<?php
class AssessmentEntity  implements JsonSerializable
{
    protected $id;
    protected $name;
    protected $description;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data) {
        // no id if we're creating
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->name = $data['name'];
        $this->description = $data['description'];
    }

    public function jsonSerialize()
    {
        return [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description
        ];
    }
    
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getDescription() {
        return $this->description;
    }

}