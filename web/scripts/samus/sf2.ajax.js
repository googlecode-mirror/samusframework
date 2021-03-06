function SF() {

    this.loadingMsg = "Aguarde...";

    this.tinyMCEClass = "editor"; // classe dos editores tinyMCE para ser tratada de forma diferente 

    /**
     * Obtem o um arry associativo com todos os dados de um formulário, esta função
     * é destinada para
     * @param form HTMLFormElement
     * @param camposNomeArray Array
     * @return Array
     */
    this.arrayFromForm = function(form , camposNomeArray) {
        var dataArray = {};

        formId = $(form).attr("id");

        for(var i = 0 ; i < camposNomeArray.length ; i ++) {

            if($('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]').is("[type='radio']")) {
                if($('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]:checked').val() != undefined) {
                    dataArray[camposNomeArray[i]] = $('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]:checked').val();
                }
            } else {

                if($('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]').is("[type='checkbox']"))  {

                    var items = [];

                    $('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]:checked').each(function() {
                        items.push($(this).val());
                    });

                    dataArray[camposNomeArray[i]] = items;
                    //dataArray[camposNomeArray[i]].push($('#' + form.id + " " +  '[@name="'+camposNomeArray[i]+'[]"]:checked').val());

                } else {

                    if($('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]').is("."+this.tinyMCEClass)) {
                        dataArray[camposNomeArray[i]] = tinyMCE.get(($('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]').attr("id"))).getContent();;
                    } else {
                        if($('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]').val() != undefined) {
                            dataArray[camposNomeArray[i]] = $('#' + formId + " " +  '[name="'+camposNomeArray[i]+'"]').val();
                        }
                    }
                }

            }
        }

        return dataArray;

    }


    /**
     * Realiza uma ação de POST genérica a partir de um formulário
     * @param url string caminho para o arquivo que preocessará o formulário
     * @param form HTMLFormElement formulário
     * @param camposNomeArray Array array com o nome dos campos do formulário que serão processados
     * @param resultElementId id do elemento que receberá a resposta
     * @return string
     */
    this.saveForm = function (url , form , camposNomeArray , resultElementId) {

        dataArray = this.arrayFromForm(form , camposNomeArray);


        $("#"+resultElementId).html(this.loadingMsg);

        $.post(url , dataArray, function(msg){
            $("#"+resultElementId).html(msg);
        });
    }

    /**
     * Faz o carregamento de um controlador qualquer de forma assincrona, o
     * resultado do carregamento da URL fica armazenado no elemento ID indicado
     * @param url string url do controlador que será processado (pode ser igual à qualquer outra página_
     * @param resultElementId string o id de um elemento qualquer que receberá os resultados do carregamento
     * @param dataArray Array um array associativo dos valroes que deverão ser enviados à página carregada
     * @return void
     *
     */
    this.ajaxLoad = function (url , resultElementId , dataArray) {
        $("#"+resultElementId).html(this.loadingMsg);
        $.post(url, dataArray, function(result){
            $("#"+resultElementId).html(result);
        });
    }

    /**
     * Faz o carregamento de um controlador qualquer de forma assincrona, o
     * resultado do carregamento da URL fica armazenado no elemento ID indicado
     * @param url string url do controlador que será processado (pode ser igual à qualquer outra página_
     * @param resultElementId string o id de um elemento qualquer que receberá os resultados do carregamento
     * @param dataArray Array um array associativo dos valroes que deverão ser enviados à página carregada
     * @return void
     *
     */
    this.ajaxLoadAppend = function (url , resultElementId , dataArray) {
        //$("#"+resultElementId).html(this.loadingMsg);
        $.post(url, dataArray, function(result){
            $("#"+resultElementId).append(result);
        });
    }


}


sf = new SF();
sf.loadingMsg = "<img src='../media/images/admin/loading.gif' alt='Loading...' title='Aguarde... Carregando' />";
