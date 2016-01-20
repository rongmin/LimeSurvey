<?php
/**
 * Survey default view
 *
 */
 $count= 0;

     //TODO : move to controller
     $templates = getTemplateListWithPreviews();
     //print_r($templates);
     $count = 0;
     $surveyid = $surveyinfo['sid'];
     $setting_entry = 'quickaction_'.Yii::app()->user->getId();
     $quickactionstate = getGlobalSetting($setting_entry);

     $surveylocale = Permission::model()->hasSurveyPermission($iSurveyID, 'surveylocale', 'read');
     // EDIT SURVEY SETTINGS BUTTON
     $surveysettings = Permission::model()->hasSurveyPermission($iSurveyID, 'surveysettings', 'read');
     $respstatsread = Permission::model()->hasSurveyPermission($iSurveyID, 'responses', 'read')
         || Permission::model()->hasSurveyPermission($iSurveyID, 'statistics', 'read')
         || Permission::model()->hasSurveyPermission($iSurveyID, 'responses', 'export');



?>
<div class="side-body">

    <!-- Quick Actions -->
    <h3 id="survey-action-title"><?php eT('Survey quick actions'); ?><span data-url="<?php echo Yii::app()->urlManager->createUrl("admin/survey/sa/togglequickaction/");?>" id="survey-action-chevron" class="glyphicon glyphicon-chevron-up"></span></h3>
        <div class="row welcome survey-action" id="survey-action-container" style="<?php if($quickactionstate==0){echo 'display:none';}?>">
            <div class="col-lg-12 content-right">

                <!-- Alerts, infos... -->
                <div class="row">
                    <div class="col-lg-12">

                        <!-- While survey is activated, you can't add or remove group or question -->
                        <?php if ($activated == "Y"): ?>
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
                                <strong><?php eT('Warning!');?></strong> <?php eT('While survey is activated, you can\'t add or remove group or question');?>
                            </div>

                        <?php elseif(!$groups_count > 0):?>

                            <!-- To add questions, first, you must add a question group -->
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
                                <strong><?php eT('Warning!');?></strong> <?php eT('To add questions, first, you must add a question group.');?>
                            </div>

                            <!-- If you want a single page survey, just add a single group, and switch on "Show questions group by group -->
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
                                <span class="glyphicon glyphicon-info-sign" ></span>&nbsp;&nbsp;&nbsp;
                                <?php eT('If you want a single page survey, just add a single group, and switch on "Show questions group by group"');?>
                            </div>
                        <?php endif;?>
                    </div>
                </div>

                <!-- Boxes and template -->
                <div class="row">

                    <!-- Boxes -->
                    <div class="col-sm-6">

                        <!-- Switch : Show questions group by group -->
                        <?php $switchvalue = ($surveyinfo['format']=='G') ? 1 : 0 ; ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="groupbygroup"><?php eT('Show questions group by group :');?></label>
                                <?php $this->widget('yiiwheels.widgets.switch.WhSwitch', array(
                                    'name' => 'groupbygroup',
                                    'id'=>'switchchangeformat',
                                    'value'=>$switchvalue,
                                ));?>
                                <input type="hidden" id="switch-url" data-url="<?php echo $this->createUrl("admin/survey/sa/changeFormat/surveyid/".$surveyinfo['sid']);?>" />
                                <br/><br/>
                            </div>
                        </div>


                        <!-- Add Question / group -->
                        <div class="row">
                            <!-- Survey active, so it's impossible to add new group/question -->
                            <?php if ($activated == "Y"): ?>

                                    <!-- Can't add new group to survey  -->
                                    <div class="col-lg-6">
                                        <div class="panel panel-primary disabled" id="pannel-1">
                                            <div class="panel-heading">
                                                <h4 class="panel-title"><?php eT('Add group');?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <a  href="#" data-toggle="tooltip" data-placement="bottom" title="<?php eT("This survey is currently active."); ?>" style="display: inline-block" data-toggle="tooltip">
                                                    <span class="icon-add text-success"  style="font-size: 3em;"></span>
                                                </a>
                                                <p><a href="#"><?php eT('Add new group');?></a></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Can't add a new question -->
                                    <div class="col-lg-6" >
                                        <div class="panel panel-primary disabled" id="pannel-2">
                                            <div class="panel-heading">
                                                <h4 class="panel-title  disabled"><?php eT('Add question');?></h4>
                                            </div>
                                            <div class="panel-body  ">
                                                <a href="#" data-toggle="tooltip" data-placement="bottom" title="<?php eT("This survey is currently active."); ?>" style="display: inline-block" data-toggle="tooltip">
                                                    <span class="icon-add text-success"  style="font-size: 3em;"></span>
                                                </a>
                                                <p>
                                                    <a  href="#" data-toggle="tooltip" data-placement="bottom" title="<?php eT("This survey is currently active."); ?>" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Survey cannot be activated. Either you have no permission or there are no questions.'); ?>">
                                                        <?php eT("Add new question"); ?>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- survey is not active, and user has permissions, so buttons are shown and active -->
                                <?php elseif(Permission::model()->hasSurveyPermission($surveyinfo['sid'],'surveycontent','create')): ?>

                                    <!-- Add group -->
                                    <div class="col-lg-6">
                                        <div class="panel panel-primary panel-clickable" id="pannel-1" data-url="<?php echo $this->createUrl("admin/questiongroups/sa/add/surveyid/".$surveyinfo['sid']); ?>">
                                            <div class="panel-heading">
                                                <h4 class="panel-title"><?php eT('Add group');?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <a  href="<?php echo $this->createUrl("admin/questiongroups/sa/add/surveyid/".$surveyinfo['sid']); ?>" >
                                                    <span class="icon-add text-success"  style="font-size: 3em;"></span>
                                                </a>
                                                <p><a href="<?php echo $this->createUrl("admin/questiongroups/sa/add/surveyid/".$surveyinfo['sid']); ?>"><?php eT('Add new group');?></a></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Survey has no group, so can't add a question -->
                                    <?php if(!$groups_count > 0): ?>
                                        <div class="col-lg-6" >
                                            <div class="panel panel-primary disabled" id="pannel-2">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title  disabled"><?php eT('Add question');?></h4>
                                                </div>
                                                <div class="panel-body  ">
                                                    <a href="#" data-toggle="tooltip" data-placement="bottom" title="<?php eT("You must first create a question group."); ?>" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Survey cannot be activated. Either you have no permission or there are no questions.'); ?>">
                                                        <span class="icon-add text-success"  style="font-size: 3em;"></span>
                                                    </a>
                                                    <p>
                                                        <a  href="#" data-toggle="tooltip" data-placement="bottom" title="<?php eT("You must first create a question group."); ?>" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Survey cannot be activated. Either you have no permission or there are no questions.'); ?>" >
                                                            <?php eT("Add new question"); ?>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Survey has a group, so can add a question -->
                                    <?php else:?>
                                        <div class="col-lg-6">
                                            <div class="panel panel-primary panel-clickable" id="pannel-2" data-url="<?php echo $this->createUrl("admin/questions/sa/newquestion/surveyid/".$surveyinfo['sid']); ?>">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title"><?php eT('Add question');?></h4>
                                                </div>
                                                <div class="panel-body">
                                                    <a  href="<?php echo $this->createUrl("admin/questions/sa/newquestion/surveyid/".$surveyinfo['sid']); ?>" >
                                                        <span class="icon-add text-success"  style="font-size: 3em;"></span>
                                                    </a>
                                                    <p><a href="<?php echo $this->createUrl("admin/questions/sa/newquestion/surveyid/".$surveyinfo['sid']); ?>")"><?php eT("Add new question"); ?></a></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">


                                <!-- Edit text elements and general settings -->
                                <?php if($surveylocale && $surveysettings): ?>
                                    <div class="panel panel-primary panel-clickable" id="pannel-3" data-url="<?php echo $this->createUrl("admin/survey/sa/editlocalsettings/surveyid/".$surveyinfo['sid']); ?>">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?php eT('Edit text elements and general settings');?></h4>
                                        </div>
                                        <div class="panel-body">
                                            <a  href="<?php echo $this->createUrl("admin/survey/sa/editlocalsettings/surveyid/".$surveyinfo['sid']); ?>" >
                                                <span class="icon-edit text-success"  style="font-size: 3em;"></span>
                                            </a>
                                            <p><a href="<?php echo $this->createUrl("admin/survey/sa/editlocalsettings/surveyid/".$surveyinfo['sid']); ?>"><?php eT('Edit text elements and general settings');?></a></p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="panel panel-primary disabled" id="pannel-3" >
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?php eT('Edit text elements and general settings');?></h4>
                                        </div>
                                        <div class="panel-body">
                                            <a href="#" data-toggle="tooltip" data-placement="bottom" title="<?php eT("We are sorry but you don't have permissions to do this."); ?>" style="display: inline-block" data-toggle="tooltip" >
                                                <span class="icon-edit text-success"  style="font-size: 3em;"></span>
                                            </a>
                                            <p><a href="#"><?php eT('Edit text elements and general settings');?></a></p>
                                        </div>
                                    </div>
                                <?php endif;?>
                            </div>


                            <!-- Stats -->
                            <?php if($respstatsread && $activated=="Y"):?>
                                <div class="col-lg-6">
                                    <div class="panel panel-primary panel-clickable" id="pannel-4" data-url="<?php echo $this->createUrl("admin/statistics/sa/index/surveyid/".$surveyinfo['sid']); ?>">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?php eT("Responses & statistics");?></h4>
                                        </div>
                                        <div class="panel-body">
                                            <a  href="<?php echo $this->createUrl("admin/statistics/sa/index/surveyid/".$surveyinfo['sid']); ?>" >
                                                <span class="glyphicon glyphicon-stats text-success"  style="font-size: 3em;"></span>
                                            </a>
                                            <p>
                                                <a href="<?php echo $this->createUrl("admin/statistics/sa/index/surveyid/".$surveyinfo['sid']); ?>">
                                                    <?php eT("Responses & statistics");?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-lg-6">
                                    <div class="panel panel-primary disabled" id="pannel-4">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?php eT("Responses & statistics");?></h4>
                                        </div>
                                        <div class="panel-body">
                                            <a  href="#" >
                                                <span class="glyphicon glyphicon-stats text-success"  style="font-size: 3em;"></span>
                                            </a>
                                            <p>
                                                <a href="#" title="<?php if($activated!="Y"){eT("This survey is not active - no responses are available.");}else{eT("We are sorry but you don't have permissions to do this.");} ?>" style="display: inline-block" >
                                                    <?php eT("Responses & statistics");?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <!-- Template carroussel -->
                        <?php $this->renderPartial( "/admin/survey/subview/_template_carousel", array(
                            'templates'=>$templates,
                            'surveyinfo'=>$surveyinfo,
                            'iSurveyId'=>$surveyid,
                        )); ?>
                    </div>

                    <!-- last visited question -->
                    <?php if($showLastQuestion):?>
                        <div class="row text-left">
                            <div class="col-lg-12">
                                <?php eT("Last visited question:");?>
                                <a href="<?php echo $last_question_link;?>" class=""><?php echo $last_question_name;?></a>
                                <br/><br/>
                            </div>
                        </div>
                    <?php endif;?>

                </div> <!-- row boxes and template-->


            </div>
        </div>


    <!-- Survey summary -->
    <h3><?php eT('Survey summary'); ?></h3>
        <div class="row">
            <div class="col-lg-12 content-right">

                <!-- for very small screens -->
                <div class="hidden-sm  hidden-md hidden-lg ">
                    <p>
                        <strong><?php eT("Title");?>:</strong><br/>
                        <?php echo flattenText($surveyinfo['surveyls_title'])." (".gT("ID")." ".$surveyinfo['sid'].")";?>
                    </p>

                    <p>
                        <strong><?php echo gT("Survey URL");?> :</strong><br/>
                        <small><em><?php echo getLanguageNameFromCode($surveyinfo['language'],false); ?></em></small><br/>
                            <?php $tmp_url = $this->createAbsoluteUrl("survey/index",array("sid"=>$surveyinfo['sid'],"lang"=>$surveyinfo['language'])); ?>
                            <?php
                                // TODO : move to controller
                                $textLink = substr ( $tmp_url, 0 , 56 );
                                if ( strlen($textLink) < strlen($tmp_url) )
                                    $textLink .= '...';
                            ?>
                            <small><a href='<?php echo $tmp_url?>' target='_blank'><?php echo $textLink;?></a>
                        </small>

                    </p>
                </div>

                <!-- Table for big screens -->
                <table class="items table hidden-xs" id='surveydetails'>
                    <thead>

                        <!-- Title -->
                        <tr>
                            <th><?php eT("Title");?>:</th>
                            <th><?php echo flattenText($surveyinfo['surveyls_title'])." (".gT("ID")." ".$surveyinfo['sid'].")";?></th>
                        </tr>
                    </thead>

                    <!-- Survey URL -->
                    <tr>
                        <td>
                            <strong> <?php echo gT("Survey URL");?> :</strong>
                        </td>
                        <td>
                        </td>
                    </tr>

                    <!-- Base language -->
                    <tr>
                        <td style="border-top: none; padding-left: 2em">
                            <small><?php echo getLanguageNameFromCode($surveyinfo['language'],false); ?></small>
                        </td>
                        <td style="border-top: none;" >
                            <?php $tmp_url = $this->createAbsoluteUrl("survey/index",array("sid"=>$surveyinfo['sid'],"lang"=>$surveyinfo['language'])); ?>
                            <small><a href='<?php echo $tmp_url?>' target='_blank'><?php echo $tmp_url; ?></a></small>
                        </td>
                    </tr>

                    <!-- Additional languages  -->
                    <?php foreach ($aAdditionalLanguages as $langname): ?>
                        <tr>
                            <td  style="border-top: none; padding-left: 2em">
                                <small><?php echo getLanguageNameFromCode($langname,false).":";?></small>
                            </td>
                            <td  style="border-top: none;" >
                                <?php $tmp_url = $this->createAbsoluteUrl("/survey/index",array("sid"=>$surveyinfo['sid'],"lang"=>$langname)); ?>
                                <small><a href='<?php echo $tmp_url?>' target='_blank'><?php echo $tmp_url; ?></a></small>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- End URL -->
                    <tr>
                        <td   style="border-top: none; padding-left: 2em">
                            <small><?php eT("End URL");?>:</small>
                        </td>
                        <td style="border-top: none">
                            <small><?php echo $endurl;?></small>
                        </td>
                    </tr>

                    <!-- Survey's texts -->
                    <tr>
                        <td><strong><?php eT("Survey's texts");?> :</strong></td>
                        <td></td>
                    </tr>

                    <!-- Description -->
                    <tr>
                        <td style="border-top: none; padding-left: 2em">
                            <small><?php eT("Description:");?></small>
                        </td>
                        <td style="border-top: none;" >
                            <small>
                            <?php
                                if (trim($surveyinfo['surveyls_description']) != '')
                                {
                                    templatereplace(flattenText($surveyinfo['surveyls_description']));
                                    echo LimeExpressionManager::GetLastPrettyPrintExpression();
                                }
                            ?>
                            </small>
                        </td>
                    </tr>

                    <!-- Welcome -->
                    <tr>
                        <td style="border-top: none; padding-left: 2em">
                            <small><?php eT("Welcome:");?></small>
                        </td>
                        <td style="border-top: none;" >
                            <small>
                            <?php
                                templatereplace(flattenText($surveyinfo['surveyls_welcometext']));
                                echo LimeExpressionManager::GetLastPrettyPrintExpression();
                            ?>
                            </small>
                        </td>
                    </tr>

                    <!-- End message -->
                    <tr>
                        <td style="border-top: none; padding-left: 2em">
                            <small><?php eT("End message:");?></small>
                        </td>
                        <td style="border-top: none;" >
                            <small>
                            <?php
                                templatereplace(flattenText($surveyinfo['surveyls_endtext']));
                                echo LimeExpressionManager::GetLastPrettyPrintExpression();
                            ?>
                            </small>
                        </td>
                    </tr>

                    <!-- Languages -->
                    <tr>
                        <td>
                            <strong><?php eT('Languages');?>:</strong>
                        </td>
                        <td></td>
                    </tr>

                    <!-- Base language -->
                    <tr>
                        <td style="border-top: none; padding-left: 2em">
                            <small><?php eT("Base language:");?></small>
                        </td>
                        <td style="border-top: none;" >
                            <small><?php echo $language;?></small>
                        </td>
                    </tr>

                    <!-- Additional languages -->
                    <?php foreach ($aAdditionalLanguages as $langname): ?>
                        <tr>
                            <?php if($count==0): ?>
                                <td style="border-top: none; padding-left: 2em">
                                    <small><?php eT("Additional languages:");?>
                                </td>
                                <?php $count++;?>
                            <?php else:?>
                                <td style="border-top: none; padding-left: 2em"></td>
                            <?php endif;?>

                            <td  style="border-top: none;">
                               <small> <?php echo getLanguageNameFromCode($langname,false);?></small>
                            </td>
                        </tr>
                    <?php endforeach;?>

                    <!-- Administrator -->
                    <tr>
                        <td>
                            <strong><?php eT("Administrator:");?></strong>
                        </td>
                        <td>
                            <?php echo flattenText("{$surveyinfo['admin']} ({$surveyinfo['adminemail']})");?>
                        </td>
                    </tr>

                    <!-- Fax to -->
                    <?php if (trim($surveyinfo['faxto'])!=''): ?>
                        <tr>
                            <td>
                                <strong><?php eT("Fax to:");?></strong>
                            </td>
                            <td>
                                <?php echo flattenText($surveyinfo['faxto']);?>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <!-- Start date/time -->
                    <tr>
                        <td>
                            <strong><?php eT("Start date/time:");?></strong>
                        </td>
                        <td>
                            <?php echo $startdate;?>
                        </td>
                    </tr>

                    <!-- Expiry date/time -->
                    <tr>
                        <td>
                            <strong><?php eT("Expiry date/time:");?></strong>
                        </td>
                        <td>
                            <?php echo $expdate;?>
                        </td>
                    </tr>

                    <!-- Template -->
                    <tr>
                        <td>
                            <strong><?php eT("Template:");?></strong>
                        </td>
                        <td>
                            <?php $templatename = $surveyinfo['template'];
                            if (Permission::model()->hasGlobalPermission('templates','read'))
                            {
                                $templateurl_url = $this->createAbsoluteUrl("admin/templates/sa/view/editfile/startpage.pstpl/screenname/welcome/templatename/$templatename"); ?>
                                <a href='<?php echo $templateurl_url?>' target='_blank'><?php echo $templatename; ?></a>
                                <?php
                            }
                            else
                            {
                                echo $templatename;
                            }
                            ?>
                        </td>

                    </tr>

                    <!-- Number of questions/groups -->
                    <tr>
                        <td>
                            <strong><?php eT("Number of questions/groups");?>:</strong>
                        </td>
                        <td>
                            <?php echo $sumcount3."/".$sumcount2;?>
                        </td>
                    </tr>

                    <!-- Survey currently active -->
                    <tr>
                        <td>
                            <strong><?php eT("Survey currently active");?>:</strong>
                        </td>
                        <td>
                            <?php echo $activatedlang;?>
                        </td>
                    </tr>

                    <!-- Survey table name -->
                    <?php if($activated=="Y"): ?>
                        <tr>
                            <td>
                                <strong><?php eT("Survey table name");?>:</strong>
                            </td>
                            <td>
                                <?php echo $surveydb;?>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <!-- Hints  -->
                    <tr>
                        <td>
                            <strong><?php eT("Hints");?>:</strong>
                        </td>
                        <td>
                            <?php echo $warnings.$hints;?>
                        </td>
                    </tr>

                    <!-- usage -->
                    <?php if ($tableusage != false){
                            if ($tableusage['dbtype']=='mysql' || $tableusage['dbtype']=='mysqli'){
                                $column_usage = round($tableusage['column'][0]/$tableusage['column'][1] * 100,2);
                                $size_usage =  round($tableusage['size'][0]/$tableusage['size'][1] * 100,2); ?>
                                <tr><td><strong><?php eT("Table column usage");?>: </strong></td><td><div class='progressbar' style='width:20%; height:15px;' name='<?php echo $column_usage;?>'></div> </td></tr>
                                <tr><td><strong><?php eT("Table size usage");?>: </strong></td><td><div class='progressbar' style='width:20%; height:15px;' name='<?php echo $size_usage;?>'></div></td></tr>
                            <?php }
                            elseif (($arrCols['dbtype'] == 'mssql')||($arrCols['dbtype'] == 'postgre')||($arrCols['dbtype'] == 'dblib')){
                                $column_usage = round($tableusage['column'][0]/$tableusage['column'][1] * 100,2); ?>
                                <tr><td><strong><?php eT("Table column usage");?>: </strong></td><td><strong><?php echo $column_usage;?>%</strong><div class='progressbar' style='width:20%; height:15px;' name='<?php echo $column_usage;?>'></div> </td></tr>
                            <?php }
                        } ?>
                </table>
            </div>
        </div>
</div>