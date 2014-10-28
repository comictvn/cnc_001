<?php
namespace Albumanh\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class AlbumanhTable
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
	
	public function getAlbumanhitem($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('image');
		$select->where(array('album'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$Albumanh = array();
		for($i=0; $i<$results->count(); $i++)
		{
			$kq = $results->current();
			$Albumanh[] = $kq;
			$results->next();
		}
		return $Albumanh;
	}
	
	public function getimage($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('image');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$Albumanh = array();
		for($i=0; $i<$results->count(); $i++)
		{
		$kq = $results->current();
		$Albumanh[] = $kq;
		$results->next();
		}
		return $Albumanh;
	}
	
	public function countitem($dbAdapter, $id)
	{
		$sql=new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('image');
		$select->columns(array('id'));
		$select->join('albumanh','albumanh.id=image.album',array('id'),$select::JOIN_INNER);
		$select->where(array('albumanh.id'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i=0;$i<$results->count();$i++)
		{
		$kq=$results->current();
		$product[]=$kq;
		$results->next();
		}
		return count($product);
	}
	
	public function deleteimage($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('image');
		$delete->where(array('album'=>$id));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function insertimage($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$insert = $sql->insert('image');
		$insert->values($data);
		$selectString = $sql->getSqlStringForSqlObject($insert);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deleteimagealbum($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('image');
		$delete->where(array('album'=>$data['album']));
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
	
	public function saveMember(Albumanh $Albumanh)
	{
		$data = array(
				'id'=>$Albumanh->id,
				'image'=>$Albumanh->image,
				'name'=>$Albumanh->name,
				'alias'=>$Albumanh->alias,
				'tag'=>$Albumanh->tag,
				'summary'=>$Albumanh->summary,
				'description'=>$Albumanh->description,
				'active'=>$Albumanh->active,
		);
		$id=(int)$Albumanh->id;
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
	
	public function deletecontact($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('proinfocontact');
		$delete->where(array('idinfocontact'=>$id));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
}