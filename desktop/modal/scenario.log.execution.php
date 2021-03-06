<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
  throw new Exception(__('Aucun scénario ne correspondant à : ', __FILE__) . init('scenario_id'));
}
sendVarToJs('scenarioLog_scenario_id', init('scenario_id'));
?>

<div style="display: none;width : 100%" id="div_alertScenarioLog"></div>
<?php echo '<span style="font-weight: bold;font-size:1.5em;">' . $scenario->getHumanName() . '</span>'; ?>
<div class="input-group pull-right" style="display:inline-flex">
  <input class="form-control input-sm roundedLeft" id="in_scenarioLogSearch" style="width : 200px;margin-left:5px;" placeholder="{{Rechercher}}" />
  <span class="input-group-btn">
    <a class="btn btn-warning btn-sm" data-state="1" id="bt_scenarioLogStopStart"><i class="fas fa-pause"></i> {{Pause}}</a><a class="btn btn-success btn-sm" id="bt_scenarioLogDownload"><i class="fas fa-cloud-download-alt"></i> {{Télécharger}}</a><a class="btn btn-danger roundedRight btn-sm" id="bt_scenarioLogEmpty"><i class="fas fa-trash"></i> {{Vider le log}}</a>
  </span>
</div>
<br/><br/>
<pre id='pre_scenariolog' style='overflow: auto; height: calc(100% - 70px);width:100%;'></pre>

<script>
jeedom.log.autoupdate({
  log : 'scenarioLog/scenario'+scenarioLog_scenario_id+'.log',
  display : $('#pre_scenariolog'),
  search : $('#in_scenarioLogSearch'),
  control : $('#bt_scenarioLogStopStart'),
})

$('#bt_scenarioLogEmpty').on('click', function() {
  jeedom.scenario.emptyLog({
    id: <?php echo init('scenario_id') ?>,
    error: function(error) {
      $('#div_alertScenarioLog').showAlert({message: error.message, level: 'danger'})
    },
    success: function() {
      $('#div_alertScenarioLog').showAlert({message: '{{Log vidé avec succès}}', level: 'success'})
      $.clearDivContent('pre_logScenarioDisplay')
    }
  })
})

$('#bt_scenarioLogDownload').click(function() {
  window.open('core/php/downloadFile.php?pathfile=log/scenarioLog/scenario<?php echo init('scenario_id') ?>.log', "_blank", null)
})
</script>
