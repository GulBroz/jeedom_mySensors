<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'mySensors');
$eqLogics = eqLogic::byType('mySensors');
$dico = mySensors::$_dico;
foreach($dico['S'] as &$value){
$value = __($value[1],__FILE__);
}
foreach($dico['N'] as &$value){
$value = __($value,__FILE__);
}
foreach($dico['C'] as &$value){
$value = __($value,__FILE__);
}
sendVarToJS('mySensorDico', $dico)

?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes mySensors}}
        </legend>
        <?php
        if (count($eqLogics) == 0) {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Aucun mySensors détecté, démarrer un node pour ajout}}</span></center>";
        } else {
            ?>
            <div class="eqLogicThumbnailContainer">
                <?php
                $dir = dirname(__FILE__) . '/../../doc/images/';
                $files = scandir($dir);
                foreach ($eqLogics as $eqLogic) {
                    $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                    echo "<center>";
                    $test = 'node_' . $eqLogic->getConfiguration('icone') . '.png';
                    log::add('mySensors', 'debug', 'test ' . $test);
                    if (in_array($test, $files)) {
                        $path = 'node_' . $eqLogic->getConfiguration('icone');
                    } else {
                        $path = 'mySensors_icon';
                    }
                    echo '<img src="plugins/mySensors/doc/images/' . $path . '.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
                ?>
            </div>
        <?php } ?>
    </div>


    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <div class="row">
            <div class="col-sm-6">
                <form class="form-horizontal">
            <fieldset>
                <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i>  {{Général}}
                <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
                </legend>
                <div class="form-group">
                    <label class="col-md-2 control-label">{{Nom du Node}}</label>
                    <div class="col-md-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement mySensors}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label" >{{Objet parent}}</label>
                    <div class="col-md-3">
                        <select class="form-control eqLogicAttr" data-l1key="object_id">
                            <option value="">{{Aucun}}</option>
                            <?php
                            foreach (object::all() as $object) {
                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">{{Catégorie}}</label>
                    <div class="col-md-8">
                        <?php
                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                            echo '<label class="checkbox-inline">';
                            echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                            echo '</label>';
                        }
                        ?>

                    </div>
                </div>
                <div class="form-group">
                <label class="col-sm-2 control-label" ></label>
                <div class="col-sm-9">
                 <input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>
                  <input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>
                </div>
                </div>
                            <div class="form-group expertModeVisible">
                                <label class="col-md-2 control-label">{{Délai max entre 2 messages}}</label>
                                <div class="col-md-8">
                                    <input class="eqLogicAttr form-control" data-l1key="timeout" placeholder="Délai maximum autorisé entre 2 messages (en mn)"/>
                                </div>
                            </div>
                            <div class="form-group expertModeVisible">
                                <label class="col-md-2 control-label">{{Type de piles}}</label>
                                <div class="col-md-8">
                                    <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_type" placeholder="Doit être indiqué sous la forme : 3xAA"/>
                                </div>
                            </div>
                            <div class="form-group">
                    <label class="col-sm-2 control-label">{{Commentaire}}</label>
                    <div class="col-md-8">
                        <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
                    </div>
                </div>

            </fieldset>

        </form>
        </div>

                <div id="infoNode" class="col-sm-6">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>{{Configuration}}</legend>

                        <div class="form-group">
                    		<label class="col-md-2 control-label">{{ID du Node}}</label>
                    		<div class="col-md-3">
                    		 <span class="eqLogicAttr" data-l1key="configuration" data-l2key="nodeid"></span>
                    		</div>

                    		<label class="col-md-2 control-label">{{Version mySensors}}</label>
                    		<div class="col-md-3">
                        	<span class="eqLogicAttr" data-l1key="configuration" data-l2key="LibVersion"></span>
                    		</div>

                	</div>

                        <div id="infoSketch" class="form-group">
                    		<label class="col-md-2 control-label">{{Nom du Sketch}}</label>
                		 <div class="col-md-3">
                		  <span class="eqLogicAttr" data-l1key="configuration" data-l2key="SketchName"></span>
                    		</div>

                    	        <label class="col-md-2 control-label">{{Version du Sketch}}</label>
                    		<div class="col-md-3">
                        	<span class="eqLogicAttr" data-l1key="configuration" data-l2key="SketchVersion"></span>
                    		</div>

                	</div>

                	<div class="form-group">
                    		<label class="col-md-2 control-label">{{Dernière Activité}}</label>
                    		<div class="col-md-3">
                        	<span class="eqLogicAttr" data-l1key="configuration" data-l2key="updatetime"></span>
                    		</div>

                    		<label class="col-md-2 control-label">{{Batterie}}</label>
                    		<div class="col-md-3">
                    		 <span class="eqLogicAttr" data-l1key="configuration" data-l2key="battery"></span>
                    		</div>
                	</div>

                	<div class="form-group">
                    		<label class="col-md-2 control-label">{{Documentation}}</label>
                    		<div class="col-md-3">
                        	<a href="http://doc.jeedom.fr/fr_FR/doc_mySensors_modules.html" class="btn btn-default"><i class="fa fa-book"></i> Documentation</a>
                    		</div>

                    		<label class="col-md-2 control-label">{{Redémarrer le Node}}</label>
                    		<div class="col-md-3">
				<a class="btn btn-default" id="bt_restartEq"><i class="fa fa-power-off"></i> Redémarrer</a>
				</div>
                	</div>

                	<legend>{{Paramètres du Sketch}}</legend>
                	<div class="form-group">
                    		<label class="col-md-2 control-label">{{Catégorie du noeud}}</label>
                    		<div class="col-md-3">
                        	<select id="sel_icon" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="icone">
								<option value="">{{Aucun}}</option>
								<option value="433">{{RF433}}</option>
								<option value="barometre">{{Baromètre}}</option>
								<option value="boiteauxlettres">{{Boite aux Lettres}}</option>
								<option value="chauffage">{{Chauffage}}</option>
								<option value="compteur">{{Compteur}}</option>
								<option value="contact">{{Contact}}</option>
								<option value="feuille">{{Culture}}</option>
								<option value="custom">{{Custom}}</option>
								<option value="dimmer">{{Dimmer}}</option>
								<option value="energie">{{Energie}}</option>
								<option value="garage">{{Garage}}</option>
								<option value="humidity">{{Humidité}}</option>
								<option value="humiditytemp">{{Humidité et Température}}</option>
								<option value="hydro">{{Hydrométrie}}</option>
								<option value="ir2">{{Infra Rouge}}</option>
								<option value="jauge">{{Jauge}}</option>
								<option value="light">{{Luminosité}}</option>
								<option value="meteo">{{Météo}}</option>
								<option value="motion">{{Mouvement}}</option>
								<option value="multisensor">{{Multisensor}}</option>
								<option value="prise">{{Prise}}</option>
								<option value="relay">{{Relais}}</option>
								<option value="rfid">{{RFID}}</option>
								<option value="teleinfo">{{Téléinfo}}</option>
								<option value="temp">{{Température}}</option>
								<option value="thermostat">{{Thermostat}}</option>
								<option value="volet">{{Volet}}</option>
							</select>
                    		</div>

                	</div>
                	<div class="form-group">
                    		<div style="text-align: center">
							<img name="icon_visu" src="" width="160" height="200"/>
							</div>
                	</div>


                    </fieldset>
                </form>
            </div>
        </div>

	<legend>{{Informations}}</legend>

  <form class="form-horizontal">
      <fieldset>
          <div class="form-actions">
              <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
              <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
          </div>
      </fieldset>
  </form>
<br />

<form class="form-horizontal">
    <fieldset>
        <div class="form-actions">
        <a class="btn btn-success btn-sm cmdAction" id="bt_addmySensorsInfo"><i class="fa fa-plus-circle"></i> {{Ajouter une commande info}}</a>
        <a class="btn btn-success btn-sm cmdAction" id="bt_addmySensorsAction"><i class="fa fa-plus-circle"></i> {{Ajouter une commande actio,}}</a>
      </div>
  </fieldset>
</form>
        <br />

        		<script>
				$('#bt_restartEq').on('click', function () {
					var nodeId = $('#nodeId').text();
					$.ajax({// fonction permettant de faire de l'ajax
						type: "POST", // methode de transmission des données au fichier php
						url: "plugins/mySensors/core/ajax/mySensors.ajax.php", // url du fichier php
						data: {
							action: "restartEq",
							node: nodeId,
						},
						dataType: 'json',
						error: function (request, status, error) {
							handleAjaxError(request, status, error);
						},
						success: function (data) { // si l'appel a bien fonctionné
							if (data.state != 'ok') {
								$('#div_alert').showAlert({message: data.result, level: 'danger'});
								return;
							}
						$('#div_alert').showAlert({message: 'Le node a été relancé', level: 'success'});
						}
					});
				});
			</script>
        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 150px;">{{Nom}}</th>
                    <th style="width: 110px;">{{Sous-Type}}</th>
                    <th>{{Capteur}}</th>
                    <th style="width: 100px;">{{Donnée}}</th>
                    <th style="width: 200px;">{{Paramètres}}</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

    </div>
</div>

<?php include_file('desktop', 'mySensors', 'js', 'mySensors'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>

<script>
	$( "#sel_icon" ).change(function(){
			var text = 'plugins/mySensors/doc/images/node_' + $("#sel_icon").val() + '.png';
			//$("#icon_visu").attr('src',text);
			document.icon_visu.src=text;
 });
</script>