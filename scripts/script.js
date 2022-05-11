$(document).ready(function () {

    //sur tablette, fermeture du menu latéral au clic sur le bouton de login
    if ($(window).width() < 1024) {
        $('#connectLink').children('a').on('click', function () {
            $('#menusp').children('i').toggleClass('fa-bars').toggleClass('fa-times')
            $('#menuHaut').hide()
        });
    }

    //date du jour dans la barre supérieure
    $(function() {
        let date = new Date();
        let j = date.getDay();
        let n = date.getDate();
        let m = date.getMonth();
        let y = date.getFullYear();
        let semaine = Array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
        let mois = Array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        $.each(semaine, function(index, val) {
            if(index == j) {
                j = val;
            }
        });
        $.each(mois, function(index, val) {
            if(index == m) {
                m = val;
            }
        });

        let jour = j + ' ' + n + ' ' + m + ' ' + y;
        $('#dateJour').text(jour);
    });    

    //mise en place de l'éditeur de texte dans le formulaire d'ajout et de modification
    $('#contenu').trumbowyg({
        defaultLinkTarget: '_blank',
        minimalLinks: true,
        lang: 'fr',
        btnsDef: {
            image: {
                dropdown: ['insertImage', 'base64'],
                ico: 'insertImage'
            }
        },
        btns: [
            ['fontsize'],
            ['foreColor', 'backColor'],
            ['strong', 'em', 'del'],
            ['table'],
            ['link'],
            ['image'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ],

    });

    //mise en place du flux rss sur la page d'accueil
    $('#rssAccueil').FeedEk({
        FeedUrl: 'https://www.industrie-techno.com/rss/',
        MaxCount: 3,
        ShowDesc: true,
        ShowPubDate: true,
        DescCharacterLimit: 100,
        TitleLinkTarget: '_blank'
    });

    //réglages des effets de la zone de recherche
    $('#searchForm').submit(function (event) {
        event.preventDefault();
        event.stopPropagation();
        req = $('#request').val().trim();
        if (req != '') {
            window.location.href = 'index.php?page=search&action=results&req=' + req;
        }
    });

    //majuscule automatique au début du champ titre
    $('form#ajout').children('input#titre').keypress(function () {
        $(this).val($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1));
    });

    //apparition et disparition de l'encart d'aide pour les uploads
    $('#aidePost').on('mouseenter', function () {
        $('#aidePost').children('span').fadeIn();
    }).on('mouseleave', function() {
        $('#aidePost').children('span').fadeOut();
    });

    //apparition et disparition du menu sur smartphone et tablette
    $('#menusp').click(function () {
        $('#menusp').children('i').toggleClass('fa-bars').toggleClass('fa-times');
        $('#menuHaut').toggle();
    });

    //contrôle de champ pour l'ajout d'une nouvelle section
    $('#nvSec').keypress(function () {
        $('#response').text('');
        $(this).val($(this).val().toLowerCase());
    })
    $('#newSection').submit(function (e) {
        $('#nvSec').val($.trim($('#nvSec').val()));
        if (checkSection($('#nvSec'), $('.titreSec')) != '') {
            e.preventDefault();
            $('#response').text(checkSection());
        }
    });

    function checkSection($champTitre, $tableau) {
        $exist = '';
        if (($champTitre.val() == 'memo') || ($champTitre.val() == 'mémo')) {
            $exist = 'Ce nom de section est réservé par le système. Veuillez en choisir un autre.';
        }
        $tableau.each(function () {
            if (($(this).text() == $champTitre.val())) {
                $exist = 'Cette section existe déjà. Veuillez choisir un autre nom.';
            }
        })
        return $exist;
    }

    //contrôle de champ pour la modification d'une section
    $('#majSection').children('#section').keypress(function () {
        $('#response').text('');
        $(this).val($(this).val().toLowerCase());
    });
    $('#majSection').submit(function (e) {
        $(this).children('#section').val($.trim($(this).children('#section').val()));
        existingSec = $('#listeSec').children('li');
        existingSec.each(function () {
            if ($(this).text() == $('#majSection').children('#section').val()) {
                e.preventDefault();
                $('#response').text('Cette section existe déjà. Veuillez choisir un autre nom');
            }
        })
        if (($('#majSection').children('#section').val() == 'memo') || ($('#majSection').children('#section').val() == 'mémo')) {
            e.preventDefault();
            $('#response').text('Ce nom est réservé par le système. Veuillez en choisir un autre');
        }

    });

    //actions et contrôles du formulaire de gestion des posts
    $('#mgmtPostsMove').click(function (e) {
        $('#mgmtPostsForm').attr('action', 'index.php?page=informatique&action=mgmtmoveposts');
        if (($('input[name="mgmtPost[]"]:checked').length == 0)) {
            window.alert('Veuillez sélectionner au moins un post');
        } else if ($('select').find('option:selected').prop('disabled') == true) {
            window.alert('Veuillez sélectionner une section dans la liste');
        } else {
            $('#mgmtPostsForm').submit();
        }
    });
    $('#mgmtPostsDelete').click(function () {
        $('#mgmtPostsForm').attr('action', 'index.php?page=informatique&action=mgmtmultidelete');
        if (($('input[name="mgmtPost[]"]:checked').length == 0)) {
            window.alert('Veuillez sélectionner au moins un post');
        } else {
            if (window.confirm('Voulez-vous supprimer ce(s) post(s) ? Cette action est irréversible')) {
                $('#mgmtPostsForm').submit();
            };
        }
    });

    /*contrôle du champ et validation du renommage de section de gestion des emplacements*/
    $('#mgmtUpdateSection').children('#section').keypress(function () {
        $('#retourSection').text('');
        $(this).val($(this).val().toLowerCase());
    });
    $('#mgmtEditSection').on('click', function (e) {
        e.preventDefault();
        $('#mgmtUpdateSection').children('#section').val($.trim($('#mgmtUpdateSection').children('#section').val()));
        let message = nvSec();
        console.log(message);
        if (message == '') {
            $('#mgmtUpdateSection').submit();
        } else {
            $('#retourSection').text(message);
        }
    });
    //vérification sur le champ nom de section
    function nvSec() {
        service = $('#mgmtUpdateSection').children('#service').val();
        section = $('#mgmtUpdateSection').children('#section').val();
        existingSec = $('#mgmtListeSec').children('li');
        message = '';
        if ((section == 'memo') || (section == 'mémo')) {
            message = 'Ce nom est réservé par le système, veuillez en choisir un autre.';
        }
        $.each(existingSec, function () {
            if ($(this).text() == section) {
                message = 'Cette section existe déjà !';
            }
        });
        return message;
    }

    /*contrôle du champ et validation d'ajout d'une section depuis la gestion des emplacements*/
    $('#mgmtAddSection').children('#section').keypress(function () {
        $('#retourSection').text('');
        $(this).val($(this).val().toLowerCase());
    });
    $('#mgmtAddSectionBtn').on('click', function (e) {
        e.preventDefault();
        $('#mgmtAddSection').children('#section').val($.trim($('#mgmtAddSection').children('#section').val()));
        let message = addNvSec();
        if (addNvSec() == '') {
            $('#mgmtAddSection').submit();
        } else {
           $('#retourSection').text(message);
        }

    });
    function addNvSec() {
        var message = '';
        $.each($('.tableService'), function() {
            if(($(this).text() == $('#listeServ option:selected').text()) && ($(this).next('td.tableSection').text() == $('#section').val())) {
                message = 'Ce service a déjà une section "' + $('#section').val() + '"';
            }
        });

        if (($('#section').val() == 'memo') || ($('#section').val() == 'mémo')) {
            message = 'Ce nom est réservé par le système, veuillez en choisir un autre.';
        }
        return message;
    }

    //contrôles lors de la suppression d'emplacements
    $('#mgmtDeleteEmpBtn').click(function (e) {
        e.preventDefault();
        if (($('input[name="mgmtEmp[]"]:checked').length == 0)) {
            window.alert('Veuillez sélectionner au moins une section');
        } else {
            if (window.confirm('Voulez-vous supprimer ce(s) emplacement(s) ? Cette action est irréversible et supprimera également tous les posts liés à ces sections')) {
                $('#mgmtEmpForm').submit();
            };
        }
    });


    /* accordéon des posts */
    $('.pointer').click(function () {
        if ($(this).next('.articles').css('display') == 'none') {
            $(this).next('.articles').show();
            $(this).children('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
        } else {
            $(this).next('.articles').hide();
            $(this).children('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
        }
    });

    //ouverture de l'accordéon si une ancre est indiquée dans l'url
    if ($(location).prop('hash') !== '') {
        $('span.articles').css('display', 'block');
    }

    /* mise en valeur du résultat de recherche */
    hash = $(location).prop('hash');
    if (hash !== '') {
        $('a' + hash + '').parents('article').addClass('clignote');
    }

    //lien en rouge s'il contient un logo exclamation
    $('i.fa-exclamation-triangle').parents('a').css('color', 'red');

    //controle de maximum de cases cochées pour les posts sur la page d'accueil
    $(window).on('load', disableChkbx);
    $('.homeChkbx').on('change', disableChkbx);

    function disableChkbx() {
        if ($(".homeChkbx:checked").length == 3) {
            $(".homeChkbx:not(:checked)").prop('disabled', true);
        } else if ($('.homeChkbx:checked').length < 3) {
            $('.homeChkbx').prop('disabled', false);
        }
    }

    //rappel de validation pour le choix des posts à mettre en avant
    $('#diffusionform').find('input').on('change', function () {
        console.log('change');
        $('#alertValid').show();
    })

    //carousel diffusion tv
    $('.owl-carousel').owlCarousel({
        items: 1,
        loop: true,
        margin: 10,
        autoplay: true,
        autoplayTimeout: 10000,
        onTranslated: callback
    });

    function callback(event) {
        item = event.page.index;
        count = event.page.count;
        if (item === (count + 1)) {
            setTimeout(function () {
                location.reload();
            }, 10000)
        }
    }

    // récupération d'un paramètre (sParam) dans l'url
    function GetURLParameter(sParam) {
        sPageURL = window.location.search.substring(1);
        sURLVariables = sPageURL.split('&');
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return sParameterName[1];
            }
        }
    }

    //apparition formulaire ajout guide
    $('#addGuideBtn').click(function () {
        $('#ajoutGuide').slideToggle('fast');
    })

    //paramètres d'affichage de la page de slider
    if (GetURLParameter('page') == 'diffusion') {
        $('#entete').css('border-bottom', 'none');
        $('#entete').children('a').children('img').css('display', 'none'); //supprimer cette ligne pour réafficher le logo en haut de la page
        //décommenter pour remettre la barre horizontale : $('#entete').css('border-bottom', 'thick solid #003d6a'); 
        $('footer').css('display', 'none');
        $('main').css('padding-bottom', '1em');
    }

    //paramètres du plugin d'affichage de la météo
    $('#meteo').openWeather({
        key: '',
        city: 'Nersac',
        lang: 'fr',
        iconTarget: '#meteoIcon',
        success: function () {
            $('#meteo').show();
        },
        error: function (message) {
            console.log(message);
        }
    });

    //paramètres de la galerie photos
    $("#gallery").unitegallery({
        gallery_theme: 'tiles',
        tiles_type: 'nested'
    });

    //affichage du formulaire d'ajout d'une photo dans la galerie
    $('#lienAddImg').on('click', function () {
        $('#formAddPhoto').css('display', 'flex');
    });

    //apparition du textarea au clic sur l'icone de modification du mémo
    $('#memoService').on('click', function () {
        $('#memoContenu').css('display', 'none');
        $('#formMemo').css('display', 'block');
    })

    //vidage de la zone de message de retour au changement de valeur dans les champs user et password
    $('#user').keypress(function () {
        $('#retourConnect').text('');
    });
    $('#pwd').keypress(function () {
        $('#retourConnect').text('');
    });

    //appel de la fonction de connexion, affichage d'un message de retour et rafraichissement de la page
    $(function () {
        $('#connectForm').on('submit', function (e) {
            var user = '';
            var pwd = '';
            e.preventDefault();
            user = $('#user').val().trim();
            pwd = $('#pwd').val().trim();
            $.ajax({
                type: 'POST',
                url: 'index.php?page=home&action=connect',
                data: {
                    user: user,
                    pwd: pwd
                },
                success: function (response) {
                    $('#retourConnect').text(response);
                    setTimeout(function () {
                            location.reload();
                        }, 1000);
                },
                error: function () {
                    console.log('erreur');
                }
            });
        });
    })





    // ---------------- A SUPPRIMER AU PASSAGE A SHAREPOINT ---------------------------------

    //upload d'un annuaire ou guide d'application
    $(function () {
        $('#upload').on('submit', function (e) {
            e.preventDefault();
            file_data = $('#fichierShort').prop('files')[0];
            if (file_data != undefined) {
                form_data = new FormData();
                form_data.append('file', file_data);
                $.ajax({
                    type: 'POST',
                    url: 'index.php?page=home&action=addfile',
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function (response) {
                        $('#upload').css('display', 'none');
                        $('<p>Le document a bien été importé !</p>').appendTo('#retour');
                        $('input#lien').attr('type', 'text').val('docs/' + response);
                        $('#fichierShort').val('');
                    },
                    error: function () {
                        $('<p>Une erreur s\'est produite, veuillez réessayer.</p>').appendTo('#retour');
                    }
                });
            }
            return false;
        });
    });

    //champ lien : type=texte pour le formulaire de mise à jour 
    if ($('#shortForm').children('button').text().includes('Mettre à jour')) {
        $('input#lien').prop('type', 'text');
    } 

    //upload d'une vidéo pour diffusion
    $(function () {
        $('#uploadvideo').on('submit', function (e) {
            e.preventDefault();
            file_data = $('#fichierShort').prop('files')[0];
            if (file_data != undefined) {
                form_data = new FormData();
                form_data.append('file', file_data);
                $.ajax({
                    type: 'POST',
                    url: 'index.php?page=home&action=uploadvideo',
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function (response) {
                        $('#uploadvideo').css('display', 'none');
                        $('<p>La vidéo a bien été importée !</p>').appendTo('#retour');
                        $('input#lien').attr('type', 'text').val('videos/' + response);
                        $('#fichierShort').val('');
                    },
                    error: function () {
                        $('<p>Une erreur s\'est produite, veuillez réessayer.</p>').appendTo('#retour');
                    }
                });
            }
            return false;
        });
    });

    //upload d'image dans la galerie
    $(function () {
        $('#uploadPhoto').on('submit', function (e) {
            e.preventDefault();
            if (checkExtension($('#uploadImg'), $imgArray)) {
                file_data = $('#uploadImg').prop('files')[0];
                if (file_data != undefined) {
                    form_data = new FormData();
                    form_data.append('file', file_data);
                    $.ajax({
                        type: 'POST',
                        url: 'index.php?page=galerie&action=upload',
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (response) {
                            $('#uploadPhoto').css('display', 'none');
                            $('<p>L\'image a bien été importée !</p>').appendTo('#retourImg');
                            $('input#lienPhoto').attr('type', 'text').val('img/gallery/' + response);
                            $('#uploadImg').val('');
                        },
                        error: function () {
                            $('<p>Une erreur s\'est produite, veuillez réessayer.</p>').appendTo('#retourImg');
                        }
                    });
                }
                return false;
            }
        });
    });

    //controle des extensions de fichiers uploadés
    $imgArray = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];

    function checkExtension($champFichier, $extArray) {
        var fileExtension = $extArray;
        if ($.inArray($champFichier.val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("Formats acceptés : " + fileExtension.join(', '));
            $champFichier.val('');
            return false;
        } else {
            return true;
        }
    }

    //désactivation du bouton d'upload si aucun fichier n'est sélectionné dans le formulaire des posts
    $(window).on('load', checkFile);
    $('.file').on('change', checkFile);

    function checkFile() {
        if ($('#formulairePost').find('.file').val() == '') {
            $('#validAddFile').prop('disabled', true);
        } else {
            $('#validAddFile').prop('disabled', false);
        }
    };

    //upload de fichier en ajax pour les posts
    $(function () {
        $('.submit').on('click', function () {
            page = GetURLParameter('page');
            file_data = $('.file').prop('files')[0];
            if (file_data != undefined) {
                form_data = new FormData();
                form_data.append('file', file_data);
                $.ajax({
                    type: 'POST',
                    url: 'index.php?page=' + page + '&action=addfile',
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function (response) {
                        $('<p><small>Lien du fichier :</small><span style="color:#e86616"> docs/' + response + '</span></p>').appendTo('#retourFile');
                        $('.file').val('');
                    },
                    error: function () {
                        $('<p>Une erreur s\'est produite, veuillez réessayer.</p>').appendTo('#retourFile');
                    }
                });
            }
            return false;
        });
    });
   
    //-----------------------------------------------------------------------------------------------






});