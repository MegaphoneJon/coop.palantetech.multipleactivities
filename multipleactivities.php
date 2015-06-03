<?php

require_once 'multipleactivities.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function multipleactivities_civicrm_config(&$config) {
  _multipleactivities_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function multipleactivities_civicrm_xmlMenu(&$files) {
  _multipleactivities_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function multipleactivities_civicrm_install() {
  return _multipleactivities_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function multipleactivities_civicrm_uninstall() {
  return _multipleactivities_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function multipleactivities_civicrm_enable() {
  return _multipleactivities_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function multipleactivities_civicrm_disable() {
  return _multipleactivities_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function multipleactivities_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _multipleactivities_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function multipleactivities_civicrm_managed(&$entities) {
  return _multipleactivities_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function multipleactivities_civicrm_caseTypes(&$caseTypes) {
  _multipleactivities_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function multipleactivities_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _multipleactivities_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implementation of hook_civicrm_searchTasks
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_searchTasks
 */
function multipleactivities_civicrm_searchTasks( $objectName, &$tasks ) {
  if ($objectName == 'activity') {
    $tasks[99] = array( 
      'title'  => ts( 'Copy Activities'  ),
      'class'  => 'CRM_Activity_Form_Task_Copy' 
    );
  }
}

/**
 * Implementation of hook_civicrm_buildForm
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function multipleactivities_civicrm_buildForm( $formName, &$form ) {

  if ($formName == 'CRM_Activity_Form_Activity' ) {

    //For duplicating an activity
    //Add number of copies
    $copies = array();
    for ($i = 1; $i <= 20; $i++) {
      $copies[$i] = $i;
    }
    $form->addElement('select', 'copies', ts('Total # of Sessions'), $copies); 
    $form->assign( 'copies', $copies);

    //Add date field for Activity Date
    for ($i = 2; $i <= 20; $i++) {
      $activityDate = "activityDate_$i";
      $form->addDate( "$activityDate", ts("Session Date $i"), false, array( 'formatType' => 'activityDate') );
      $form->assign( "$activityDate", $activityDate);
    }

    //For saving and copying an activity to a new form
    //Set $_SESSION variable
    if (isset($_SESSION['copied_act_id'])) {
      global $copied_act_id;
      $copied_act_id = $_SESSION['copied_act_id'];
    }
    
    //Whitelisted Activity fields to provide as defaults
    $activity_defaults = array( 126 => 'cse_staff_name_126', 
                                95 => 'setting_code_95', 
                                132 => 'language_132', 
                                9 => 'location_9', #Region/Area 
                                125 => 'city1_125', 
                                10 => 'county_10',
                                113 => 'funding_source_113',
                                14 => 'session_type_14',
                                15 => 'curriculum_15',
                                16 => 'curriculum_other_16',
                                104 => 'primary_topic_104',
                                18 => 'session_topic_18', #Additional Topics
                                124 => 'smart_objectives_124',
                                120 => 'activity_notes_120',
                              );

    //Pull the core and custom field values when setting the new activity form
    if (isset($copied_act_id) && $copied_act_id != NULL) {
      $copied_act_params = array(
        'version' => 3,
        'sequential' => 1,
        'id' => $copied_act_id,
      );
      $copied_act = civicrm_api('Activity', 'get', $copied_act_params);

      $activity_type_id = $copied_act['values'][0]['activity_type_id'];

      if ($copied_act['count'] == 1) {
        $defaults['activity_date_time'] = date('m/d/Y', strtotime($copied_act['values'][0]['activity_date_time']));
        if (isset($copied_act['values'][0]['duration'])) {
          $defaults['duration'] = $copied_act['values'][0]['duration'];
        }
      }

      $copy_count = 1;
      if ($_SESSION['copies'] > 1) {
        $copy_count = $defaults['copies'] = $_SESSION['copies'];
        for ($i = 2; $i <= $copy_count; $i++) {
          $activityDate =  "activityDate_$i"; 
          $defaults["activityDate_$i"] = $_SESSION['copied_dates'][$i];
        }
      }

      //Name of Organization (With Contact)
      $act_with_contact_query = "SELECT cac.contact_id, cc.sort_name
                                   FROM civicrm_activity_contact cac
                                   JOIN civicrm_contact cc
                                     ON cac.contact_id = cc.id
                                  WHERE cac.record_type_id = 3
                                    AND cac.activity_id = $copied_act_id";
      $act_with_contact = CRM_Core_DAO::executeQuery($act_with_contact_query, CRM_Core_DAO::$_nullArray);

      $act_with_contact_count = 0;

      while ($act_with_contact->fetch()) {
        $defaults['target_contact'][$act_with_contact_count] = $act_with_contact->contact_id;
        $act_with_contact_count++;

        if (isset($defaults['target_contact_value'])) {
          $defaults['target_contact_value'] .= "; $act_with_contact->sort_name";
        } else {
          $defaults['target_contact_value'] = "$act_with_contact->sort_name";
        }
      }

      if (!CRM_Utils_Array::crmIsEmptyArray($defaults['target_contact'])) {
        $target_contact_value = explode(';', trim($defaults['target_contact_value']));
        $target_contact = array_combine(array_unique($defaults['target_contact']), $target_contact_value);

        $form->assign('prePopulateData', $form->formatContactValues($target_contact));
      }

      //Custom Data
      //Get custom data tables
      $act_custom_tables_query = " SELECT id, table_name
                                     FROM civicrm_custom_group
                                    WHERE extends = 'Activity'
                                      AND (extends_entity_column_value IS NULL
                                       OR extends_entity_column_value LIKE '%$activity_type_id%')";
      $act_custom_tables = CRM_Core_DAO::executeQuery($act_custom_tables_query, CRM_Core_DAO::$_nullArray);
  
      //Get custom data columns within each table
      while ($act_custom_tables->fetch()) {
        $act_custom_table_id = $act_custom_tables->id;
        if ($act_custom_table_id == 4 || #Encounter Info custom data fieldset
            $act_custom_table_id == 24 || #Funding and Fees custom data fieldset
            $act_custom_table_id == 3 || #Educational Presentation custom data fieldset
            $act_custom_table_id == 26 || #Smart Objectives custom data fieldset
            $act_custom_table_id == 25 ) { #Activity Notes custom data fieldset
          $act_custom_table_name = $act_custom_tables->table_name;
          $act_custom_table_columns_query = " SELECT id, column_name
                                                FROM civicrm_custom_field
                                               WHERE custom_group_id = $act_custom_table_id";
          $act_custom_table_columns = CRM_Core_DAO::executeQuery($act_custom_table_columns_query, CRM_Core_DAO::$_nullArray);
          $act_custom_table_column_names = array();

          //Put custom data tables into an array
          while ($act_custom_table_columns->fetch()) {
            $act_custom_table_column_names["$act_custom_table_columns->id"] = "$act_custom_table_columns->column_name";
          }

          //Prepare select statements for SELECT query
          $copied_act_custom_table_select = implode(", ", $act_custom_table_column_names);
  
          //Custom data of copied activity
          $new_act_custom_data_defaults_query = "SELECT $copied_act_custom_table_select
                                                   FROM $act_custom_table_name
                                                  WHERE entity_id = $copied_act_id";
          $new_act_defaults = CRM_Core_DAO::executeQuery($new_act_custom_data_defaults_query, CRM_Core_DAO::$_nullArray);

          while ($new_act_defaults->fetch()) {
            foreach ($act_custom_table_column_names as $column_id => $column_name) {
              if (array_key_exists($column_id, $activity_defaults)) {
                $default_value = $new_act_defaults->$column_name;
                $custom_default_key = 'custom_' . $column_id . '_-1';
                switch ($column_id) {
                  case 126: #CSE Staff Name
                    $default_value = trim($default_value, CRM_Core_DAO::VALUE_SEPARATOR);
                    $default_value = explode(CRM_Core_DAO::VALUE_SEPARATOR, $default_value);
                    foreach ($default_value as $key => $value) {
                      $defaults["$custom_default_key"]["$value"] = "$value";
                    }
                    break;
                  case 125: #City
                    $defaults["$custom_default_key" . "_id"] = $default_value;
                    $defaults["$custom_default_key"] = $default_value;
                    break;
                  case 18: #Additional Topics
                    $default_value = trim($default_value, CRM_Core_DAO::VALUE_SEPARATOR);
                    $default_value = explode(CRM_Core_DAO::VALUE_SEPARATOR, $default_value);
                    foreach ($default_value as $key => $value) {
                      $defaults["$custom_default_key"][$value] = 1;
                    }
                    break;
                  default:
                    $defaults["$custom_default_key"] = $default_value;
                }
              }
            }
          }
        }
      } 

      $form->setDefaults($defaults);
      if (!(isset($form->_defaultValues['activity_date_time_time']))) {
        unset($_SESSION['copied_act_id']);
      }
      if ($copy_count > 1) {
        unset($_SESSION['copies']);
        unset($_SESSION['copied_dates']);
      }
    }

    //Add 'Save and Copy' button.  Need to redeclare all buttons and dependent variables.
    $message = array(
      'completed' => ts('Are you sure? This is a COMPLETED activity with the DATE in the FUTURE. Click Cancel to change the date / status. Otherwise, click OK to save.'),
      'scheduled' => ts('Are you sure? This is a SCHEDULED activity with the DATE in the PAST. Click Cancel to change the date / status. Otherwise, click OK to save.'),
    );
    $js = array('onclick' => "return activityStatus(" . json_encode($message) . ");");
    foreach ($form->_elementIndex as $element => $index) {
      if ($element =='buttons') {
        if ($form->_elements[$index]->_elements['0']->_attributes['value'] != 'Delete' &&
            $form->_action != 4) {
          $form->addButtons(array(
              array(
                'type' => 'upload',
                'name' => ts('Save'),
                'subName' => 'view',
                'js' => $js,
                'isDefault' => TRUE
              ),
              array(
                'type' => 'upload',
                'name' => ts('Save and Copy'),
                'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                'subName' => 'new',
                'js' => $js,
              ),
              array(
                'type' => 'cancel',
                'name' => ts('Cancel')
              )
            )
          );
        }
      }
    }
  }
}

/**
 * Implementation of hook_civicrm_postProcess
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function multipleactivities_civicrm_postProcess( $formName, &$form ) {
  if ($formName == 'CRM_Activity_Form_Activity' ) {

    //For duplicating an activity
    if (isset($form->_submitValues['copies']) && $form->_submitValues['copies'] >= 2) {
      $copies = $form->_submitValues['copies'];
      $first_session_date = date('Y-m-d h:i:s', strtotime($form->_submitValues['activity_date_time']));
      $last_session_date = date('Y-m-d h:i:s', strtotime($form->_submitValues["activityDate_$copies"]));

      //Get Old Activity
      $old_act_id = $form->_activityId;
      $old_act_params = array('version' => 3,'sequential' => 1,'id' => $old_act_id,);
      $old_act = civicrm_api('Activity', 'get', $old_act_params);
    
      $old_act_session_update_query = " UPDATE civicrm_value_session_details_27
                                           SET session_number_127 = 1, 
                                               date_of_first_session_129 = '$first_session_date', 
                                               date_of_last_sessions_130 = '$last_session_date', 
                                               number_of_sessions_131 = $copies
                                         WHERE entity_id = $old_act_id";
      CRM_Core_DAO::executeQuery($old_act_session_update_query, CRM_Core_DAO::$_nullArray);

      //Create New Activty from Old Activity
      if ($old_act['count'] == 1) {

          for ($i = 2; $i <= $copies; $i++) {
            $new_act_params = $old_act['values'][0];
            unset($new_act_params['id']);
            $new_act_params['version'] = 3;
            $new_act_params['sequential'] = 1;
            //Override old activity date with date set in copy interface
            $activityDate =  "activityDate_$i"; 
            if ($form->_submitValues["$activityDate"] != '' ) {
              $new_act_params['activity_date_time'] = $form->_submitValues["activityDate_$i"];
            }
            $new_act = civicrm_api('Activity', 'create', $new_act_params);
  
            //Add With Contacts to New Activity
            if ($new_act['is_error'] == 0) {
              $new_act_id = $new_act['id'];
              $old_act_with_query = " SELECT contact_id 
                                        FROM civicrm_activity_contact
                                       WHERE activity_id = $old_act_id 
                                         AND record_type_id = 3";
              $old_act_with = CRM_Core_DAO::executeQuery($old_act_with_query, CRM_Core_DAO::$_nullArray);
  
              while ($old_act_with->fetch()) {
                $new_act_with_query = " INSERT INTO civicrm_activity_contact
                                                SET contact_id = $old_act_with->contact_id, 
                                                    activity_id = $new_act_id,
                                                    record_type_id = 3";
                $new_act_with = CRM_Core_DAO::executeQuery($new_act_with_query, CRM_Core_DAO::$_nullArray);
              }
  
              //Custom Data
              //Get custom data tables
              $activityTypeId = $old_act['values'][0]['activity_type_id'];
              $act_custom_tables_query = " SELECT id, table_name
                                             FROM civicrm_custom_group
                                            WHERE extends = 'Activity'
                                              AND (extends_entity_column_value IS NULL
                                                  OR extends_entity_column_value LIKE '%$activityTypeId%')";
              $act_custom_tables = CRM_Core_DAO::executeQuery($act_custom_tables_query, CRM_Core_DAO::$_nullArray);

  
              //Get custom data columns within each table
              while ($act_custom_tables->fetch()) {
                $act_custom_table_id = $act_custom_tables->id;
                $act_custom_table_name = $act_custom_tables->table_name;
                $act_custom_table_columns_query = " SELECT id, column_name
                                                      FROM civicrm_custom_field
                                                     WHERE custom_group_id = $act_custom_table_id";
                $act_custom_table_columns = CRM_Core_DAO::executeQuery($act_custom_table_columns_query, CRM_Core_DAO::$_nullArray);
                $act_custom_table_column_names = array();
  
                //Put custom data tables into an array
                while ($act_custom_table_columns->fetch()) {
                  $act_custom_table_column_names["$act_custom_table_columns->id"] = "$act_custom_table_columns->column_name";
                }

                //Prepare select statements for  INSERT query
                $old_act_custom_table_select = implode(", ", $act_custom_table_column_names);
                $old_act_custom_table_select = trim("$new_act_id, " . $old_act_custom_table_select, ', ');
                $new_act_custom_table_select = implode(", ", $act_custom_table_column_names);
                $new_act_custom_table_select = trim("entity_id, " . $new_act_custom_table_select, ', ');

                //Copy custom data of old activity to the new one
                if ( $act_custom_table_name != 'civicrm_value_session_details_27' ) {
                  $new_act_custom_data_insert_query = "INSERT INTO $act_custom_table_name
                                                                   ($new_act_custom_table_select)
                                                            SELECT $old_act_custom_table_select
                                                              FROM $act_custom_table_name
                                                             WHERE entity_id = $old_act_id";
                  CRM_Core_DAO::executeQuery($new_act_custom_data_insert_query, CRM_Core_DAO::$_nullArray);
                }

              }

              $new_act_session_update_query = " UPDATE civicrm_value_session_details_27
                                                   SET session_number_127 = $i, 
                                                       date_of_first_session_129 = '$first_session_date', 
                                                       date_of_last_sessions_130 = '$last_session_date', 
                                                       number_of_sessions_131 = $copies
                                                 WHERE entity_id = $new_act_id";
              CRM_Core_DAO::executeQuery($new_act_session_update_query, CRM_Core_DAO::$_nullArray);

              $new_act_duplicate_update_query = " UPDATE civicrm_value_encounter_info_4
                                                     SET duplicate_participants_69 = 1
                                                   WHERE entity_id = $new_act_id";
              CRM_Core_DAO::executeQuery($new_act_duplicate_update_query, CRM_Core_DAO::$_nullArray);

            }
          }
      }
    }

    //For saving and copying an activity to a new form
    $buttonName = $form->controller->getButtonName();
    if ($buttonName == $form->getButtonName('upload', 'new')) {
      $session = CRM_Core_Session::singleton();
      $resetStr = "reset=1&action=add&context=standalone&atype={$form->_activityTypeId}";
      $session->replaceUserContext(CRM_Utils_System::url('civicrm/activity', $resetStr));
      $_SESSION['copied_act_id'] = $form->_activityId;
      if ($copies > 1) {
        $_SESSION['copies'] = $copies;
        for ($i = 2; $i <= $copies; $i++) {
          $activityDate =  "activityDate_$i"; 
          if ($form->_submitValues["$activityDate"] != '' ) {
            $_SESSION['copied_dates'][$i] = $form->_submitValues["activityDate_$i"];
          }
        }
      }
    }
  }
}

/**
 * Implementation of hook_civicrm_postProcess
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function multipleactivities_civicrm_post( $op, $objectName, $objectId, &$objectRef ) {
  if ( $objectName == 'Activity' && $op == 'create' ) {

    $first_session_date = $last_session_date = date('Y-m-d h:i:s', strtotime($objectRef->activity_date_time));
    $act_session_create_query = " INSERT INTO civicrm_value_session_details_27
                                              (session_number_127, date_of_first_session_129, date_of_last_sessions_130, number_of_sessions_131, entity_id)
                                       VALUES (1, '$first_session_date', '$last_session_date', 1, $objectId)";
    CRM_Core_DAO::executeQuery($act_session_create_query, CRM_Core_DAO::$_nullArray);

  }
}
