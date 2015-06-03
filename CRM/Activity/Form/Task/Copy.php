<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 * $Id$
 *
 */

/**
 * This class provides the functionality to delete a group of
 * Activites. This class provides functionality for the actual
 * deletion.
 */
class CRM_Activity_Form_Task_Copy extends CRM_Activity_Form_Task {

  /**
   * build all the data structures needed to build the form
   *
   * @return void
   * @access public
   */
  function preProcess() {
    parent::preProcess();
  }

  /**
   * Build the form
   *
   * @access public
   *
   * @return void
   */
  function buildQuickForm() {
    //Add number of copies
    $copies = array();
    for ($i = 1; $i <= 20; $i++) {
      $copies[$i] = $i;
    }
    $this->addElement('select', 'copies', ts('Total # of Sessions'), $copies); 
    $this->assign( 'copies', $copies);

    //Add radio buttons for Duplicate data
    $duplicateOptions = array('1' => ts('Yes'), '0' => ts('No'));
    foreach ($duplicateOptions as $key => $var) {
      $duplicateParticipants[$key] = HTML_QuickForm::createElement('radio', null, ts('Duplicate participants'), $var, $key);
    }
    $this->addGroup($duplicateParticipants, 'duplicate_participants', ts('Duplicate participants'));
    $this->assign( 'duplicate_participants', $duplicate_participants);

    //Add date field for Activity Date
    
    for ($i = 1; $i <= 20; $i++) {
      $activityDate = "activityDate_$i";
      $this->addDate( "$activityDate", ts("Activity Date $i"), false, array( 'formatType' => 'activityDate') );
      $this->assign( "$activityDate", $activityDate);
    }

    $defaults['duplicate_participants'] = '1';
    $this->setDefaults($defaults);

    $this->addDefaultButtons(ts('Copy Activites'), 'done');
  }

  /**
   * process the form after the input has been submitted and validated
   *
   * @access public
   *
   * @return None
   */
  public function postProcess() {
    $duplicate = $this->_submitValues['duplicate_participants'];
    $copies = $this->_submitValues['copies'];
    
    for ($i = 1; $i <= $copies; $i++) {

      foreach ($this->_activityHolderIds as $activityId['id']) {

        //Get Old Activity
        $old_act_id = $activityId['id'];
        $old_act_params = array('version' => 3,'sequential' => 1,'id' => $old_act_id,);
        $old_act = civicrm_api('Activity', 'get', $old_act_params);

        //Create New Activty from Old Activity
        if ($old_act['count'] == 1) {
          $new_act_params = $old_act['values'][0];
          unset($new_act_params['id']);
          $new_act_params['version'] = 3;
          $new_act_params['sequential'] = 1;
          //Override old activity date with date set in copy interface
          $activityDate =  "activityDate_$i"; 
          if ($this->_submitValues["$activityDate"] != '' ) {
            $new_act_params['activity_date_time'] = $this->_submitValues["activityDate_$i"];
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
              $old_act_custom_table_select = "$new_act_id, " . $old_act_custom_table_select;
              $new_act_custom_table_select = implode(", ", $act_custom_table_column_names);
              $new_act_custom_table_select = "entity_id, " . $new_act_custom_table_select;

              //Copy custom data of old activity to the new one
              $new_act_custom_data_insert_query = "INSERT INTO $act_custom_table_name
                                                               ($new_act_custom_table_select)
                                                        SELECT $old_act_custom_table_select
                                                          FROM $act_custom_table_name
                                                         WHERE entity_id = $old_act_id";
              CRM_Core_DAO::executeQuery($new_act_custom_data_insert_query, CRM_Core_DAO::$_nullArray);

              //Set copied activity as duplicate data
              if ($act_custom_table_id == 4 &&
                  $duplicate != '' ) {
                $new_act_custom_data_update_query = " UPDATE civicrm_value_encounter_info_4
                                                         SET duplicate_participants_69 = $duplicate
                                                       WHERE entity_id = $new_act_id";
                CRM_Core_DAO::executeQuery($new_act_custom_data_update_query, CRM_Core_DAO::$_nullArray);
              }
            }
          } else {
            CRM_Core_Session::setStatus("", ts('Activity with and custom data Not Copied'), "info");
          }
        } else {
          CRM_Core_Session::setStatus("", ts('Activity Not Copied, not unique'), "info");
        }
      }

      CRM_Core_Session::setStatus($copies, ts('Copied Activities'), "success");
      CRM_Core_Session::setStatus("", ts('Total Selected Activities: %1', array(1 => count($this->_activityHolderIds))), "info");
    }
  }

}
