<?php

class somethingController extends something
{
	function triggerAddMemberMenu($module_info)
	{
		$config = $this->getConfig();
		if ($config->use != "Y")
		{
			return;
		}

		if ($config->mid_name == "")
		{
			return;
		}

		$oMemberController = getController('member');
		$oMemberModel = getModel('member');

		$target_srl = Context::get('target_srl');
		$target_mid = $config->mid_name;

		$memberInfo = $oMemberModel->getMemberInfoByMemberSrl($target_srl);

		if ($config->connect_address_type == 'user_id')
		{
			$url = getUrl('', 'mid', $target_mid, 'smember', $memberInfo->user_id);
		}
		else if ($config->connect_address_type == 'nick_name')
		{
			$url = getUrl('', 'mid', $target_mid, 'smember', $memberInfo->nick_name);
		}
		else
		{
			$url = getUrl('', 'mid', $target_mid, 'smember', $target_srl);
		}

		$str = $config->memeber_popupmenu_name;
		$oMemberController->addMemberPopupMenu($url, $str, '', '_self');
	}

	function triggerInsertAfterDocument($obj)
	{
		if (!Context::get('is_logged'))
		{
			return;
		}

		$oSomethingModel = getModel('something');
		$config = $this->getConfig();

		if ($config->use != "Y")
		{
			return;
		}

		if ($obj->status != "PUBLIC")
		{
			return;
		}

		$oSomethingModel->updateMemberRecentActivity($obj);
	}

	function triggerInsertAfterComment($obj)
	{
		if (!Context::get('is_logged'))
		{
			return;
		}

		$oSomethingModel = getModel('something');
		$config = $this->getConfig();

		if ($config->use != "Y")
		{
			return;
		}

		if ($obj->is_secret != "N")
		{
			return;
		}

		/** @var documentModel $oDocumentModel */
		$oDocumentModel = getModel('document');
		$oDocument = $oDocumentModel->getDocument($obj->document_srl);
		if ($oDocument->get('status') != "PUBLIC")
		{
			return;
		}

		$oSomethingModel->updateMemberRecentActivity($obj);
	}

	function triggerDeleteAfterDocument($obj)
	{
		$this->deleteMemberRecentActivity($obj);
		return;
	}

	function triggerDeleteAfterComment($obj)
	{
		$this->deleteMemberRecentActivity($obj);
		return;
	}

	function deleteMemberRecentActivity($obj)
	{
		if (!Context::get('is_logged'))
		{
			return;
		}
		
		$oMemberModel = getModel('member');
		$oSomethingModel = getModel('something');

		$config = $this->getConfig();

		if ($config->use != "Y")
		{
			return;
		}

		if (!$obj->member_srl)
		{
			return;
		}

		$memberInfo = $oMemberModel->getMemberInfoByMemberSrl($obj->member_srl);
		$oSomethingModel->getMemeberRecentActivity($memberInfo, true);
	}
}
/* End of file */
