{*
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
*}
{* Confirmation of Activity copy  *}
{literal}
  <style type="text/css">
    .hidden-date {display:none;}
  </style>
{/literal}
<div class="crm-block crm-form-block crm-activity_copy-form-block">
<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
<div class="messages status no-popup">
    <div class="icon inform-icon"></div>
        <p>{ts}Are you sure you want to copy the selected Activities?{/ts}</p>
        <p>{include file="CRM/Activity/Form/Task.tpl"}</p>
    </div>
    <div class="activity-copies-field">
      <div>{$form.copies.label}</div>
      <div>{$form.copies.html}</div>
    </div>
    <br />
    <div class="activity-duplicate-participants-field">
      <div>{$form.duplicate_participants.label}</div>
      <div>{$form.duplicate_participants.html}</div>
    </div>
    <br />
    <div class="activity-date-field shown-date">
      <div class="activity-date-label">{$form.activityDate_1.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_1}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_2.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_2}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_3.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_3}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_4.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_4}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_5.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_5}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_6.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_6}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_7.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_7}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_8.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_8}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_9.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_9}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_10.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_10}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_11.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_11}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_12.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_12}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_13.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_13}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_14.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_14}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_15.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_15}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_16.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_16}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_17.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_17}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_18.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_18}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_19.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_19}</div>
    </div>
    <div class="activity-date-field hidden-date">
      <div class="activity-date-label">{$form.activityDate_20.label}</div>
      <div class="activity-date-input">{include file="CRM/common/jcalendar.tpl" elementName=activityDate_20}</div>
    </div>
<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
</div>
{literal}
  <script type="text/javascript">
    jQuery("#copies").change(function() { 
      var activityDateCountShow = jQuery('#copies').val();
      //console.log("to show: " + activityDateCountShow);
      var activityDateCountShown = jQuery('.shown-date').length
      //console.log("shown: " + activityDateCountShown);
      if (activityDateCountShown < activityDateCountShow) {
        for ( var i = activityDateCountShown; i < activityDateCountShow; i++ ) {
          console.log("interration: " + i);
          jQuery(".hidden-date").first().addClass('shown-date');
          jQuery(".hidden-date").first().removeClass('hidden-date');
        }
      } else {
        for ( var i = activityDateCountShow; i < activityDateCountShown; i++ ) {
          console.log("interration: " + i);
          jQuery(".shown-date").last().addClass('hidden-date');
          jQuery(".shown-date").last().removeClass('shown-date');
        }
      }
    });
  </script>
{/literal}
