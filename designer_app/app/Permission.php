<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail, Auth, Request, Input;


class Permission extends Model
{
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'permissions';
	
	protected $fillable = array(
							'group_id',
							'module',
							'roles');
	
	// --------------------------------------------------------------------
	
	public static function roles($group_id) 
	{
		$roles = array(
			'users' => array(
				'view'   		=> 'View',
				'login'   		=> 'Login',
				'add_edit'      => 'Add / Edit',
				'trash_restore' => 'Trash / Restore',										
			),
			'groups' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'trash_restore' => 'Delete',										
				'permission'    => 'Manage Permission',
			),
			'companies' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'trash_restore' => 'Trash / Restore',										
			),
			'suppliers' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'trash_restore' => 'Trash / Restore',										
			),
			'purchase-orders' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'report'   		=> 'Generate Report',
				'trash_restore' => 'Trash / Restore',										
			),
			'products' => array(
				'view'   		   => 'View',
				'add_edit'		   => 'Add / Edit',
				'report'   		   => 'Generate Report',
				'trash_restore'    => 'Trash / Restore',										
				'product_category' => 'Manage Product Category',
				'terms'    		   => 'Manage Terms',
				'inventory'        => 'Inventory',
			),
			'customers' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'report'   		=> 'Generate Report',
				'trash_restore' => 'Trash / Restore',										
			),
			'sales-orders' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'report'   		=> 'Generate Report',
				'trash_restore' => 'Trash / Restore',										
			),
			'expenses' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'report'   		=> 'Generate Report',
				'trash_restore' => 'Trash / Restore',										
			),
			'settings' => array(
				'edit' => 'Edit',
			),
		);		  
					  
		return $roles;
	}
	
	// --------------------------------------------------------------------
	
	public static function has_access($module, $roleInarray = array()) 
	{
		$res = FALSE;

		$user_id  = Auth::User()->id;		
		$group_id = Auth::User()->group;

		$info = Post::find($group_id);

		$post = json_decode(@$info->post_content, true);

		$roles = @$post[$module];

		if( ! $roleInarray && $roles) {
			$res = TRUE;
		}

		//if administrator
		if($group_id == 1 || $user_id == 1) return TRUE; 

	 	if( @$roles ) {
			foreach($roles as $role) {
				if(in_array($role, $roleInarray)) {
					$res = TRUE;
				}
			}
		}
						
		return $res;
	}
	
	// --------------------------------------------------------------------	

	public static function forbidden_access($segment ='') {
		if(Auth::user()->id != 1) {
			$module = ($segment) ? $segment : Request::segment(1);
			$group_id = Auth::user()->group_id;
			$p = Permission::where('module',  $module)
			               ->where('group_id', $group_id)
			               ->first();

			if(!$p) { 
				return TRUE;
			} else {
				if(Request::segment(2) && !in_array(Request::segment(2), json_decode($p->roles))) return TRUE;
			} 
		}
	}	

	// --------------------------------------------------------------------	
}
