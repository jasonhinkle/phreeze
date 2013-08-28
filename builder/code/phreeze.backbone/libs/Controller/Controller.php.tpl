<?php
/** @package    {$appname}::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/{$singular}.php");

/**
 * {$singular}Controller is the controller class for the {$singular} object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package {$appname}::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class {$singular}Controller extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	/**
	 * Displays a list view of {$singular} objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for {$singular} records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new {$singular}Criteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('{foreach from=$table->Columns item=column name=filterForEach}{$column->NameWithoutPrefix|studlycaps}{if !$smarty.foreach.filterForEach.last},{/if}{/foreach}'
				, '%'.$filter.'%')
			);

			// TODO: this is generic query filtering based only on criteria properties
			foreach (array_keys($_REQUEST) as $prop)
			{
				$prop_normal = ucfirst($prop);
				$prop_equals = $prop_normal.'_Equals';

				if (property_exists($criteria, $prop_normal))
				{
					$criteria->$prop_normal = RequestUtil::Get($prop);
				}
				elseif (property_exists($criteria, $prop_equals))
				{
					// this is a convenience so that the _Equals suffix is not needed
					$criteria->$prop_equals = RequestUtil::Get($prop);
				}
			}

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				${$plural|lower} = $this->Phreezer->Query('{$singular}',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = ${$plural|lower}->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = ${$plural|lower}->TotalResults;
				$output->totalPages = ${$plural|lower}->TotalPages;
				$output->pageSize = ${$plural|lower}->PageSize;
				$output->currentPage = ${$plural|lower}->CurrentPage;
			}
			else
			{
				// return all results
				${$plural|lower} = $this->Phreezer->Query('{$singular}',$criteria);
				$output->rows = ${$plural|lower}->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method retrieves a single {$singular} record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('{$table->GetPrimaryKeyName()|studlycaps|lcfirst}');
			${$singular|lower} = $this->Phreezer->Get('{$singular}',$pk);
			$this->RenderJSON(${$singular|lower}, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new {$singular} record and render response as JSON
	 */
	public function Create()
	{
		try
		{
			{if $table->IsView}// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
			{/if}
			
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			${$singular|lower} = new {$singular}($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

{foreach from=$table->Columns item=column}
{if $column->Extra == 'auto_increment'}
			// this is an auto-increment.  uncomment if updating is allowed
			// ${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = $this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}');

{else}
{if $column->Type == "date" or $column->Type == "datetime" or $column->Type == "timestamp"}
			${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}')));
{elseif $column->Type == "time"}
			${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = date('H:i:s',strtotime('1970-01-01 ' . $this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}')));
{else}
			${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = $this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}');
{/if}
{/if}
{/foreach}

			${$singular|lower}->Validate();
			$errors = ${$singular|lower}->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
{if !$table->PrimaryKeyIsAutoIncrement()}
				// since the primary key is not auto-increment we must force the insert here
{/if}
				${$singular|lower}->Save({if !$table->PrimaryKeyIsAutoIncrement()}true{/if});
				$this->RenderJSON(${$singular|lower}, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing {$singular} record and render response as JSON
	 */
	public function Update()
	{
		try
		{
			{if $table->IsView}// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
			{/if}
			
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('{$table->GetPrimaryKeyName()|studlycaps|lcfirst}');
			${$singular|lower} = $this->Phreezer->Get('{$singular}',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

{foreach from=$table->Columns item=column}
{if $column->Key == "PRI"}
			// this is a primary key.  uncomment if updating is allowed
			// ${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = $this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}', ${$singular|lower}->{$column->NameWithoutPrefix|studlycaps});

{elseif $column->Extra == 'auto_increment'}
			// this is an auto-increment.  uncomment if updating is allowed
			// ${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = $this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}', ${$singular|lower}->{$column->NameWithoutPrefix|studlycaps});

{else}
{if $column->Type == "date" or $column->Type == "datetime" or $column->Type == "timestamp"}
			${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}', ${$singular|lower}->{$column->NameWithoutPrefix|studlycaps})));
{elseif $column->Type == "time"}
			${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = date('Y-m-d H:i:s',strtotime('1970-01-01 ' . $this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}', ${$singular|lower}->{$column->NameWithoutPrefix|studlycaps})));
{else}
			${$singular|lower}->{$column->NameWithoutPrefix|studlycaps} = $this->SafeGetVal($json, '{$column->NameWithoutPrefix|studlycaps|lcfirst}', ${$singular|lower}->{$column->NameWithoutPrefix|studlycaps});
{/if}
{/if}
{/foreach}

			${$singular|lower}->Validate();
			$errors = ${$singular|lower}->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				${$singular|lower}->Save();
				$this->RenderJSON(${$singular|lower}, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{

{if !$table->PrimaryKeyIsAutoIncrement()}
			// this table does not have an auto-increment primary key, so it is semantically correct to
			// issue a REST PUT request, however we have no way to know whether to insert or update.
			// if the record is not found, this exception will indicate that this is an insert request
			if (is_a($ex,'NotFoundException'))
			{
				return $this->Create();
			}
{/if}

			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing {$singular} record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
			{if $table->IsView}// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
			{/if}
			
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('{$table->GetPrimaryKeyName()|studlycaps|lcfirst}');
			${$singular|lower} = $this->Phreezer->Get('{$singular}',$pk);

			${$singular|lower}->Delete();

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
}

?>
