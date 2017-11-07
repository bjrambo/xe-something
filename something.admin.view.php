<?php

class somethingAdminView extends something
{
	// TODO(BJRambo): Add to member Variables for module Info

	function init()
	{
		$this->setTemplatePath($this->module_path . 'tpl');
		$this->setTemplateFile(strtolower(str_replace('dispSomethingAdmin', '', $this->act)));
	}

	function dispSomethingAdminConfig()
	{
		$oSomethingModel = getModel('something');
		$config = $oSomethingModel->getConfig();

		Context::set('config', $config);
	}

	function dispSomethingAdminModuleInstance()
	{
		$modulePath = $this->module_path;

		$moduleSrl = Context::get('module_srl');
		if(!$moduleSrl)
		{
			if($this->module_srl)
			{
				$moduleSrl = $this->module_srl;
			}
		}

		$moduleInfo = getModel('module')->getModuleInfoByModuleSrl($moduleSrl);
		debugPrint($moduleInfo);
		debugPrint($this->module_info);

		Context::set('module_info', $moduleInfo);

		$oModuleModel = getModel('module');
		$skinList = $oModuleModel->getSkins($modulePath);
		$mSkinList = $oModuleModel->getSkins($modulePath, 'm.skins');

		/** @var $oLayoutModel layoutModel */
		$oLayoutModel = getModel('layout');
		$layoutList = $oLayoutModel->getLayoutList();
		$mLayoutList = $oLayoutModel->getLayoutList(0, 'M');

		Context::set('skin_list', $skinList);
		Context::set('mskin_list', $mSkinList);
		Context::set('layout_list', $layoutList);
		Context::set('mlayout_list', $mLayoutList);
	}
}
/* End of file */
