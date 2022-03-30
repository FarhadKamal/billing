<?php
class UsersModel extends ci_model
{

	private $users = 'users';
	private $tbl_members = 'tbl_members';
	private $tbl_roles = 'tbl_roles';
	private $tbl_accountheadlist = 'tbl_accountheadlist';

	function __construct()
	{
		parent::__construct();
	}

	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->select('users.*');
		$this->db->order_by('users.created', 'desc');
		$this->db->offset($offset);
		$this->db->limit($limit);
		return $this->db->get('users');
	}

	function count_all()
	{
		return $this->db->count_all($this->users);
	}

	function get_by_id($id)
	{
		$this->db->select('user_id,tbl_members.vMembersId,username,firstname as name,password,displayname,authlevel,vUserRole,users.created as created');
		$this->db->join('tbl_members', 'users.vMembersId = tbl_members.vMembersId', 'left');
		$this->db->join('tbl_roles', 'users.authlevel = tbl_roles.iId', 'inner');
		$this->db->where('users.user_id', $id);
		return $this->db->get($this->users);
	}

	function list_membersid()
	{
		$this->db->select('vMembersId,concat(vMembersId,' . "'#'" . ',vMembersName) as vMembersName');
		return $this->db->get($this->tbl_members);
	}

	function list_usersRole()
	{
		return $this->db->get($this->tbl_roles);
	}

	function maxId()
	{
		$query = $this->db->query('select if(max(user_id),max(user_id),0)+1 as ID from users');
		$row = $query->row(); //takes only one result row
		$wartosc = $row->ID;
		return $wartosc;
	}


	function save($data)
	{
		$this->db->insert($this->users, $data);
	}

	function update($Id, $data)
	{
		$this->db->where('user_id', $Id);
		$this->db->update($this->users, $data);
	}

	function delete($Id)
	{
		$this->db->where('user_id', $Id);
		$this->db->delete($this->users);
	}
}
