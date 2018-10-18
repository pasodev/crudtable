<?php

/*
 * For insertion we will use bindParam methods, so we 
 * don't need to sanitze the params.
 * Using query method is faster for development, but much 
 * less secure.
 *
 */
class datasource {

    private $connection;
    private $results;
    private $errors = array();

    function  __construct ($database, $user, $password, $host) 
    {
        try {
            $this->connection = new \PDO(
                'mysql:dbname=' . $database . ';host=' . $host,
                $user,
                $password
            );

        } catch (PDOException $Exception) {
            $this->errors[] = $Exception->getMessage();
        }
        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function insertEdocs($values) 
    {

        if ($this->stored( $values )) {
            return true;
        }


        $query = "INSERT INTO edocs (
            docYear, docJournal, docTitle, docAuthors, 
            docAbstract, docCitation, docTags,docKeywords, docSourceNotes, docLocation)
            values (:docYear, :docJournal, :docTitle, :docAuthors, 
            :docAbstract, :docCitation, :docTags, :docKeywords, :docSourceNotes, :docLocation)";

        $st = $this->connection->prepare($query);
        $st->bindParam(':docYear', $values[0], PDO::PARAM_INT);
        $st->bindParam(':docJournal', $values[1], PDO::PARAM_STR, 1000);
        $st->bindParam(':docTitle', $values[2], PDO::PARAM_STR, 1000);
        $st->bindParam(':docAuthors', $values[3], PDO::PARAM_STR, 1000);
        $st->bindParam(':docAbstract', $values[4], PDO::PARAM_STR);
        $st->bindParam(':docCitation', $values[5], PDO::PARAM_INT);
        $st->bindParam(':docTags', $values[6], PDO::PARAM_STR, 500);
        $st->bindParam(':docKeywords', $values[7], PDO::PARAM_STR, 255);
        $st->bindParam(':docSourceNotes', $values[8], PDO::PARAM_STR, 255);
        $st->bindParam(':docLocation', $values[9], PDO::PARAM_STR, 255);

        try {
            $result = $st->execute();
        } catch (\PDOException $Exception) {
            $result = false;
            $this->errors[] = $Exception->getMessage();
        }
        return $result; 
    }

    private function stored( $values )
    {
        $query = "SELECT * FROM edocs WHERE docTitle like :docTitle";
        $st = $this->connection->prepare( $query );
        $st->bindParam(':docTitle', $values[2]);
        try {
            $result = $st->execute();
        } catch (\PDOException $Exception) {
            $result = false;
            $this->errors[] = $Exception->getMessage();
        }
        $result = $st->fetchAll();
        return !empty($result);
    }

    public function getRows($limit, $offset)
    {
        $query = "SELECT * FROM edocs LIMIT :l OFFSET :o";
        $query = str_replace(':l', $limit, $query);
        $query = str_replace(':o', $offset, $query);

        $st = $this->connection->prepare($query);

        try {
            $result = $st->execute();
        } catch (\PDOException $Exception) {
            $result = false;
            $this->errors[] = $Exception->getMessage();
        }
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById( $id )
    {
        $query = "SELECT * FROM edocs WHERE edocId = :id";
        $st = $this->connection->prepare($query);
        $st->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $result = $st->execute();
        } catch (\PDOException $Exception) {
            $result = false;
            $this->errors[] = $Exception->getMessage();
        }
        return $st->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAmountOfRecords()
    {
        $query = "SELECT COUNT(*) AS total FROM edocs";
        $st = $this->connection->prepare($query);

        try {
            $result = $st->execute();
        } catch (\PDOException $Exception) {
            $result = false;
            $this->errors[] = $Exception->getMessage();
        }
        return $st->fetchAll(PDO::FETCH_ASSOC);

    }

    public function delete( $id )
    {
        $query = "DELETE FROM edocs WHERE edocId = :id";
        $st = $this->connection->prepare($query);
        $st->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $result = $st->execute();
        } catch (\PDOException $Exception) {
            $result = false;
            $this->errors[] = $Exception->getMessage();
        }
        return $result;
    }

    public function update( $values )
    {
        $query = "UPDATE edocs SET
            docYear = :docYear, docJournal = :docJournal, docTitle = :docTitle, docAuthors = :docAuthors, 
            docAbstract = :docAbstract, docCitation = :docCitation, 
            docTags = :docTags, docKeywords = :docKeywords, 
            docSourceNotes =:docSourceNotes, docLocation = :docLocation
            WHERE edocId = :edocId";

        $st = $this->connection->prepare($query);
        $st->bindParam(':edocId', $values['edocId'], PDO::PARAM_INT);
        $st->bindParam(':docYear', $values['docYear'], PDO::PARAM_INT);
        $st->bindParam(':docJournal', $values['docJournal'], PDO::PARAM_STR, 1000);
        $st->bindParam(':docTitle', $values['docTitle'], PDO::PARAM_STR, 1000);
        $st->bindParam(':docAuthors', $values['docAuthors'], PDO::PARAM_STR, 1000);
        $st->bindParam(':docAbstract', $values['docAbstract'], PDO::PARAM_STR);
        $st->bindParam(':docCitation', $values['docCitation'], PDO::PARAM_INT);
        $st->bindParam(':docTags', $values['docTags'], PDO::PARAM_STR, 500);
        $st->bindParam(':docKeywords', $values['docKeywords'], PDO::PARAM_STR, 255);
        $st->bindParam(':docSourceNotes', $values['docSourceNotes'], PDO::PARAM_STR, 255);
        $st->bindParam(':docLocation', $values['docLocation'], PDO::PARAM_STR, 255);

        try {
            $result = $st->execute();
        } catch (\PDOException $Exception) {
            $result = false;
            $this->errors[] = $Exception->getMessage();
        }
        return $result;


    }

    public function search( $values, $type )
    {
        $mode = ($type == "exactsearch") ? "AND" : "OR";
        $toBind = array();

        $query = "SELECT * FROM edocs WHERE ";

        foreach( $values as $field => $value ) {
            if ($value != '') {
                $query .= "$field like :$field $mode ";
                $toBind[$field] = $value;
            }
        }

        $query = substr($query, 0, -4);
        $st = $this->connection->prepare($query);

        foreach( $toBind as $f => $v) {
                $value = "%$v%";
                $field = ":$f";
                $st->bindParam($field, $value);
                unset($value);
        }
        try {
            $result = $st->execute();
        } catch (\PDOException $Exception) {
            $result = false;
            $this->errors[] = $Exception->getMessage();
        }
        return $st->fetchAll();
    }
}
