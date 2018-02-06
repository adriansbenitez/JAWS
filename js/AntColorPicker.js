/*! AntColorPicker V1.1
 * Copyright (c) 2014 AntProduction
 * http://antproduction.free.fr/AntColorPicker
 * https://github.com/antrax2013/AntColorPicker
 *
 * GPL license:
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Contributor: antrax2013@hotmail.com
 *
 */

(function ($) {
    $.fn.AntColorPicker = function(options) {

        //definition de la strucutre Html de base de la palette
        var contentTemplate = '<div id="AntColorPicker" class="AntColorPicker">';
        contentTemplate += '<ul>';
        contentTemplate += "#contentLineTemplate#";
        contentTemplate += '</ul>';
        contentTemplate += '#crossToClose#';
        contentTemplate += '</div>';

        var contentLineTemplate = '<li><a name="#color#" rel="#color#" style="background:#color#;" title="#color#"></a></li>';

        var templateCorssToClose = '<a id="CloseColorPicker" class="AntColorPickerClose" title="#labelClose#">#labelClose#</a>';

        //MÃ©thode privÃ©e remplaÃ§ant les tags
        function TagConvertor(chaine, TagList, joker) {

            var _mask = (joker == undefined)? "#":joker;

            for (var val in TagList) chaine = chaine.replace(new RegExp(_mask+val+_mask, "g"), TagList[val]);

            return chaine;
        }

        //MÃ©thode privÃ©e alimentant le template
        function contentBuilder(contentLine) {
            var content='';
            var color='';
            var values = ['00', '33', '66', '99', 'CC', 'FF'];
            for (r = 0; r < 6; r++) {
                for (g = 0; g < 6; g++) {
                    for (b = 0; b < 6; b++) {
                        color = '#' + values[r] + values[g] + values[b];
                        content += TagConvertor(contentLine,{"color":color});
                    }
                }
            }
            return content;
        }

        //On dÃ©finit nos paramÃ¨tres par dÃ©faut
        var defauts=
        {
            "iconPath": "../public/images/antColorPicker/",
            //"withIconeInInput": true,
            "withRAZOption": true,
            "withCrossToClose": true,
            "labelClose":"Fermer",
            "labelRAZColor":"RÃ©initialiser la valeur",
            "zIndex": 1500,
            "largeurPalette": 390,
            "contentTemplate": contentTemplate,
            "contentLineTemplate": contentLineTemplate,
            "builder": function(contentTempLinelate, contentTemplate, templateCorssToClose, labelClose) { //mÃ©thode construisant la palette
                var content = contentBuilder(contentTempLinelate);
                content = contentTemplate.replace("#contentLineTemplate#",content);
                return content;
            }
        };

        //Lecture des paramÃ©tres et fusion avec ceux par dÃ©faut
        var parametres=$.extend(defauts, options);

        return this.each(function () {

            var $$ = $(this);
            var oldVal = "";

            //Mise en palce de la couleur de fond en fonction de la valeur du champ
            $$.attr("style", "background-color:" + $$.val());

            //Ajout du bouton RAZ
            var id = "RaZAntColorPicker_" + Math.floor(Math.random() * 10000);
            var content = (parametres.withRAZOption == false)? '' : ' <img id="' + id + '" title="'+parametres.labelRAZColor+'" style="height:'+$$.css("height")+'" class="RaZAntColorPicker" src="css/images/vide.png" />';
            $(content).insertAfter($$);

            //if(parametres.withIconeInInput) $$.addClass('AntColorPickerIconeInput');
                //$$.css('background', "url('"+parametres.iconPath+"palette.png') no-repeat right");


            $('#' + id).bind("click", function () {
                $$.val("");
                $$.attr("style", "background-color:" + $$.val());
            });


            // RÃ©cupÃ©ration des coordonnÃ©es pour placer la palette
            var x; //= $$.offset().left;
            var y; //= $$.offset().top + $$.outerHeight(true);
            var largeurEcran = $(document).width();

            // Lorsque le curseur entre dans le champ de saisi
            $$.focusin(function (e) { start(e); });

            function start(e) {
                oldVal = $$.val();
                x = $$.offset().left;

                if ((x + parametres.largeurPalette) > largeurEcran) x -= parametres.largeurPalette;

                y = $$.offset().top + $$.outerHeight(true);
                e.stopImmediatePropagation();
                $(document).one('click', removeColorPicker);
                buildColorPicker();
            };

            $$.click(function (e) { e.stopPropagation(); });

            $$.focusout(function () {
                var tmp = $$.val();
                var reg1 = new RegExp("^[#]?[0-9A-Fa-f]{3}([0-9A-Fa-f]{3})?$", "g");

                if (!tmp.match(reg1)) $$.val(oldVal);
                if (tmp.indexOf("#") == -1 && tmp != "") $$.val("#" + $$.val());
            });

            // Fonction mettant une couleur de font claire quand la couleur choisie est foncÃ©e et vis versa
            function chooseFontColor() {
                var input = $$.val().replace("#","").toLowerCase();

                var black = false;

                for(i=0; i< 6; i+=2) black |= parseInt(input.substr(i,2),16) > 128;

                if(!black) $$.addClass('AntColorPicker-whiteFont');
                else $$.removeClass('AntColorPicker-whiteFont');
            }


            // Fonction de crÃ©ation de la palette
            function buildColorPicker() {
                // On supprime d'Ã©ventuelles autres palettes dÃ©jÃ  ouvertes
                removeColorPicker();

                // On construit le Html de la palette
                if (typeof (parametres.builder) == 'function') {
                    if(parametres.withCrossToClose!=true) {
                        parametres.contentTemplate = parametres.contentTemplate.replace("#crossToClose#","");
                    }
                    else {
                        parametres.contentTemplate = TagConvertor(contentTemplate,{ "crossToClose": templateCorssToClose, "labelClose":parametres.labelClose});
                    }

                    var content = parametres.builder(parametres.contentLineTemplate, parametres.contentTemplate);
                }
                else throw "Error: the builder parameter must be a function.";

                // On la place dans la page aux coordonnÃ©es du textfield
                $(content).css({
                    position: 'absolute',
                    left: x,
                    top: y,
                    zIndex: parametres.zIndex,
                    width: parametres.largeurPalette + 'px',
                    backgroundColor: $$.val()
                }).appendTo('body'); // Insertion dans le body html

                //$('#ColorPicker').focus();
                //$(content).appendTo('body'); // Insertion dans le body html

                // Lorsque le curseur sort du champ de saisi
                /*$('#ColorPicker').focusout(function () {
                removeColorPicker();
                });*/

                // Au survol d'une couleur, on change le fond de la palette
                $('#AntColorPicker a').hover(function () {
                    $('#AntColorPicker').css('backgroundColor', $(this).attr('rel')); // Si l'Ã©lÃ©ment Ã  dÃ©jÃ  une couleur on l'utilise
                }, function () {
                    $('#AntColorPicker').css('backgroundColor', $$.val());
                });

                // Lorsqu'une couleur est cliquÃ©, on affiche la valeur dans le textfield
                $('#AntColorPicker a').not('.AntColorPickerClose .RaZAntColorPicker').click(function () {
                    $$.val($(this).attr('rel'));
                    $$.css('backgroundColor', $(this).attr('rel'));
                    chooseFontColor();
                    removeColorPicker();
                });

                // Au survol d'une couleur, on change le fond
                $('#AntColorPicker a').mouseover(function () {
                    $('#AntColorPicker').css('backgroundColor', $(this).attr('rel'));
                });

                // On supprime la palette si le lien "Fermer" est cliquÃ©
                $('#AntColorPicker a.close').click(function () {
                    removeColorPicker();
                });
            }

            // Fonction de suppression de la palette
            function removeColorPicker() {
                $('#AntColorPicker').remove();
            }
        });
    };
})(jQuery);