<?php
/**
 * Created by PhpStorm.
 * User: The Customer
 * Date: 11/6/2019
 * Time: 9:50 PM
 */

class Model
{
    protected $db;
    protected $sql='';
    protected $whereAtr='';
    protected $selectAtr='';
    protected $updateAtr='';
    protected $joinAtr='';
    protected $setAtr ='';
    public function __construct(){
        $this->db = new Database();
    }
    //
    
    public function getAll($orderBy=''){
        $sql = (empty($orderBy)) ? 'SELECT * FROM '.strtolower(get_class($this)) : 'SELECT * FROM '.strtolower(get_class($this)).' ORDER BY '.$orderBy;
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function findBy($col,$value){
        $this->db->query("SELECT * FROM ". strtolower(get_class($this)) ." WHERE $col='$value'");
        return $this->db->single();
    }

    public function lastInsertId(){

        return $this->db->lastInsertId();
    }


    //
    public function doQuery(){
        $this->sql = $this->selectAtr. ' '. $this->joinAtr. ' '. $this->whereAtr;
        $this->db->query($this->sql);
        return $this->db->resultSet();
    }

    public function doUpdate(){
        $this->sql = $this->updateAtr. ' '. $this->setAtr. ' '. $this->whereAtr;
        $this->db->query($this->sql);
        return $this->db->execute();
    }

    //SELECT
    public function select($col="*"): self
    {
        $this->selectAtr='';
        $this->selectAtr = "SELECT ".$col. "FROM ". strtolower(get_class($this)) ;
        return $this;
    }

    public function join(string $m, $obj): self {
        $this->joinAtr ='';
        $this->joinAtr .=  " ".strtolower(get_class($this)[0]).", ". strtolower(get_class($obj)). " ". $m;
        return $this;
    }
    public function andJoin(string $m, $obj): self {
        $this->joinAtr .= ", ". strtolower(get_class($obj)). " ". $m;
        return $this;
    }
    public function where($cond): self {
        $this->whereAtr = '';
        $this->whereAtr .= " WHERE ".$cond;
        return $this;
    }
    public function and($cond): self {
        $this->whereAtr .= " AND ".$cond;
        return $this;
    }

    //UPDATE
    public function update(): self{
        $this->updateAtr='';
        $this->updateAtr = "UPDATE ".strtolower(get_class($this));
        return $this;
    }
    public function set($col, $val): self {
        if(stripos($this->sql, "UPDATE") >= 0){
            if (strstr($this->setAtr,'SET')){
                $this->setAtr .= ", ". $col. "=". $val;
            }else{
                $this->setAtr .= " SET ". $col. "=". $val;
            }

            return $this;
        }else{
            return new Model();
        }
    }
}