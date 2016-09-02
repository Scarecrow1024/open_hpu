<?php

namespace Addons\BxqScore\Controller;
use Home\Controller\AddonsController;
//defined('ACC')||exit('ACC Denied');
//像这样连接
/*
$db=new db('mysql:host=localhost;port=3306;dbname=db1', 'root', '941126');
$db->connect();
//print_r($db);
*/

//像这样插入
//$data=array('username'=>'wakkkddd','password'=>'wayyyddd');
//$db->insert("table8",$data);

//像这样更新一条语句
/*
$data=array('username'=>'eeeee');
$where='where id=46';
print_r($db->update("table8",$data,$where));
echo "<br>";
*/

//像这样获取所有的内容
//print_r($db->getAll('table8'));

//像这样获取单条内容
/*
$sql='select * from table8 where id=46';
print_r($db->getRow($sql));
echo '<br>';
*/

//像这样获取单个内容
/*
$sql='select username from table8 where id=46';
print_r($db->getOne($sql))."<br>";
*/
  class MysqlPdoController{
   protected $dbpass;
   protected $dbuser;
   protected $dsn;
   protected $stmt;
   //protected $port;

   public function __construct($dsn='',$dbuser='',$dbpass=''){
        $this->dsn=$dsn;
        //$this->port=$port;
        $this->dbuser=$dbuser;
        $this->dbpass=$dbpass;
    }
    public function connect(){   
        try{ 
            $this->pdo=new PDO($this->dsn,$this->dbuser,$this->dbpass);
        }catch(PDOException $e){ 
            echo "ERROR:".$e->getMessage();
        } 
        return $this->pdo;      
    }

    public function insert($table,$data){
        $sql = 'insert into ' . $table . ' (' . implode(',',array_keys($data)) . ')';
        $sql .= ' values (\'';
        $sql .= implode("','",array_values($data));
        $sql .= '\')';
        //echo $sql;
        //$sql="insert into ".$table.(implode("','",array_values($data)))."values".(implode(',',array_keys($data)));
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        //获取数据中的总行数，如果是插入则获取影响的行数
        if($stmt->rowCount()>0){
          //echo "insert OK";
          return true; 
        }else{
          echo "ERROR:".$e->getMessage();
        }
      }

      public function update($table,$data,$where){
        $sql = 'update ' . $table .' set ';
            foreach($data as $k=>$v) {
                $sql .= $k . "='" . $v ."',";
            }
        $sql = rtrim($sql,',');
        $sql .= $where;
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        //print_r($sql);
        //获取数据中的总行数，如果是插入则获取影响的行数
        if($stmt->rowCount()>0){
          //echo "update OK";
          return true;
        }else{
          //echo "ERROR:";
          return FALSE;
        }
      }

      //点击量加1
      public function visited(){
        $sql="update article set visited = visited+1 where id={$_GET['id']}";
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
      }

      public function getAll($sql){
        //$sql='select * from '.$table.'';
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount()>0){
          $list=array();
          while($row=$stmt->fetch()){
            $list[]=$row;
          }       
        }else{
          //echo "ERROR:".$e->getMessage();
        }
        return $list;
      }
      
      public function totals($table){
        $sql='select * from '.$table.' where is_trash=0';
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        $total=$stmt->rowCount();
        return $total;
      }
      //回收站数目
      public function tr_totals($table){
        $sql='select * from '.$table.' where is_trash=1';
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        $total=$stmt->rowCount();
        return $total;
      }

      public function getRow($sql){
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount()>0){
          $row=array();
          $row=$stmt->fetch();
        }
        return $row;
      }

      public function rowCount($table){
        $sql='select id from '.$table.' where is_trash=0';
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
      }
      

      public function getOne($sql){
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount()>0){
          $row=array();
          $row=$stmt->fetch();
        }
        return $row[0];
      }

      public function trush($sql){
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount()>0){
          echo "<script language=\"JavaScript\">\r\n"; 
          echo " alert(\"成功放入回收站\");\r\n"; 
          echo "window.location.href='index.php'";
          echo "</script>"; 
        }else{
          echo "<script language=\"JavaScript\">\r\n"; 
          echo " alert(\"删除失败\");\r\n"; 
          echo "window.location.href='index.php'";
          echo "</script>"; 
        }
      }

      public function re_trash($sql){
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount()>0){
          echo "<script language=\"JavaScript\">\r\n"; 
          echo " alert(\"撤销成功\");\r\n"; 
          echo "window.location.href='trash.php'";
          echo "</script>"; 
        }else{
          echo "<script language=\"JavaScript\">\r\n"; 
          echo " alert(\"撤销失败\");\r\n"; 
          echo "window.location.href='trash.php'";
          echo "</script>"; 
        }
      }
      
}

