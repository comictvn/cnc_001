<?php
namespace Thanhtoan\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Update;
class ThanhtoanTable
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
	
	public function getbank($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('bank');
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
	
	public function updatebank($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$insert = $sql->update('bank');
		$insert->set($data);
		$insert->where(array('id'=>$data['id']));
		$selectString = $sql->getSqlStringForSqlObject($insert);
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
	
	public function saveMember(Thanhtoan $Thanhtoan)
	{
		$data = array(
				'id'=>$Thanhtoan->id,
				'description'=>$Thanhtoan->description,
				'cod'=>$Thanhtoan->cod,
				'coddes'=>$Thanhtoan->coddes,
				'paybank'=>$Thanhtoan->paybank,
				'bankdes'=>$Thanhtoan->bankdes,
		);
		$id=(int)$Thanhtoan->id;
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