<?php
namespace Tintuc\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class TintucTable
{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway=$tableGateway;
	}
	
	public function fetchAll()
	{
		$select = $this->tableGateway->getSql()->select()->order(array('id DESC'));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	
	public function fetchAllnewcate($idcate)
	{
		$select = $this->tableGateway->getSql()->select()->where(array('category'=>$idcate,'active'=>1))->limit(3)->order(array('id DESC'));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	
	public function tinnong()
	{
		$select = $this->tableGateway->getSql()->select()
		->join('catenew', 'catenew.id = news.category',array('id', 'parent'))
		->where(array('news.index'=>1,'news.active'=>1,'catenew.parent'=>1))->order(array('catenew.id DESC'))->limit(5);
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function getdanhmuc($alias)
	{
		$select = $this->tableGateway->getSql()->select()
		->join('catenew', 'catenew.id = news.category',array('id', 'parent'))
		->where(array('news.active'=>1,'catenew.alias'=>$alias))->order(array('news.id DESC'));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function gioithieu()
	{
		$select = $this->tableGateway->getSql()->select()->where(array('active'=>1,'category'=>5))->order(array('id DESC'))->limit(5);
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function timkiem($string)
	{
		$select = $this->tableGateway->getSql()->select()->where("title like '%$string%' or title_en like '%$string%' and active = 1")->order(array('id DESC'))->limit(5);
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function duanthuchien()
	{
		$select = $this->tableGateway->getSql()->select()->where(array('active'=>1,'category'=>6))->order(array('id DESC'))->limit(6);
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function tincu()
	{
		$select = $this->tableGateway->getSql()->select()
		->join('catenew', 'catenew.id = news.category',array('id', 'parent'))
		->where(array('news.index'=>1,'news.active'=>1,'catenew.parent'=>1))->limit(6);
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function fetchtinlienquan($id)
	{
		$select = $this->tableGateway->getSql()->select()->where(array('category'=>$id))->order(array('id DESC'))->limit(3);
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function fetchdanhmuc($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('catenew');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[$kp['id']]=$kp['name'];
				$results->next();
		}
		return $loai_sp;
	}
	
	public function getseocate($dbAdapter, $alias)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('catenew');
		$select->where(array('alias'=>$alias));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$kp = $results->current();
		return $kp;
	}
	
	public function fetchTheloaitin_tuc($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('catenew');
		$select->order('id DESC');
		$select->where(array('parent'=>'1'));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]=$kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function fetchblock($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('block');
		$select->order('id DESC');
		$select->where(array('active'=>'1'));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]=$kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function getseo($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('seopage');
		$select->where(array('page'=>'Tintuc'));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
	
		$kp = $results->current();
	
		return $kp;
	}
	
	public function getcateid($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('catepro');
		$select->where(array('idcate'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[]=$kq;
		$results->next();
		}
		return $product;
	}
	
	public function deletecate($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('catepro');
		$delete->where(array('idcate'=>$id));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deleteproduct($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('product');
		$delete->where(array('id'=>$id));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function getmemberID($id)
	{
		$id=(int)$id;
		$rowset = $this->tableGateway->select(array('id'=>$id));
		$result = $rowset->current();
		if(!$result){
			throw new \Exception("Could not find row $id");
		}
		return $result;
	}
	
	public function getdetail($id)
	{
		$id = $id;
		$rowset = $this->tableGateway->select(array('alias'=>$id));
		$result = $rowset->current();
		if(!$result){
			throw new \Exception("Could not find row $id");
		}
		return $result;
	}
	
	public function saveMember(Tintuc $Tintuc)
	{
		$data = array(
				'id'=>$Tintuc->id,
				'title'=>$Tintuc->title,
				'title_en'=>$Tintuc->title_en,
				'alias'=>$Tintuc->alias,
				'index'=>$Tintuc->index,
				'category'=>$Tintuc->category,
				'resources'=>$Tintuc->resources,
				'author'=>$Tintuc->author,
				'view'=>$Tintuc->view,
				'tag'=>$Tintuc->tag,
				'image'=>$Tintuc->image,
				'active'=>$Tintuc->active,
				'summary'=>$Tintuc->summary,
				'summary_en'=>$Tintuc->summary_en,
				'description'=>$Tintuc->description,
				'description_en'=>$Tintuc->description_en,
				'meta'=>$Tintuc->meta,
				'keyword'=>$Tintuc->keyword,
				'date'=>$Tintuc->date,
		);
		$id=(int)$Tintuc->id;
		if($id == 0)
		{
			$this->tableGateway->insert($data);
				
		}
		else
		{
			if($this->getmemberID($id))
			{
				$this->tableGateway->update($data, array('id'=>$id));
			}
			else
			{
				throw new \Exception('Form id does not exist');
			}
		}
	}
	
	public function delete($id)
	{
		$query = $this->tableGateway->delete(array('id'=>$id));
		return $query;
	}
	
	//GIOI THIEU
	public function tingioithieu()
	{
		$rowset = $this->tableGateway->select(array('alias'=>'gioi-thieu'));
		$result = $rowset->current();
		
		return $result;
	}
	public function tindichvu()
	{
		$rowset = $this->tableGateway->select(array('alias'=>'dich-vu'));
		$result = $rowset->current();
	
		return $result;
	}
	public function tintuc()
	{
		$rowset = $this->tableGateway->select(array('alias'=>'tin-tuc'));
		$result = $rowset->current();
	
		return $result;
	}
}