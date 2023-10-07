/**
 * Utility functions for SelectFilterByDropdown plugin for LimeSurey
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */

/**
 * Function to filter a select by another select in the same page
 * @version 1.0.1
 * @param {integer} qID : the number of question to filter
 * @param {integer} filterqID : the number of question filtering
 */
function selectFilter_selectFilterByCode(qID,filterqID){
  $(document).ready(function(){
    var idSelectFilter = $("#question"+qID).find("select").attr('id');
    var idSelectFiltering = $("#question"+filterqID).find("select").attr('id');
    if(typeof idSelectFilter === 'undefined' || typeof idSelectFiltering === 'undefined' )
    {
      return false;
    }
    else
    {
      let idNewSelectFilter = 'select'+qID;
      let NewSelectElement = $("#"+idSelectFilter).clone().removeAttr('id').attr('id',idNewSelectFilter);
      $("#"+idSelectFilter).hide();
      $("#"+idSelectFilter).after(NewSelectElement);
      $(NewSelectElement).children().remove();
      $("#"+idSelectFilter).find('option[value=""]:first').clone().appendTo(NewSelectElement);
      $("#"+idSelectFiltering).change(function(){
        $('#'+idSelectFilter).val('');
        $('#'+idSelectFilter).trigger('change');
        $('#'+idNewSelectFilter).val('');
        var valuefilter=$(this).val();
        $('#'+idNewSelectFilter+' option').not(':first').remove();
        $('#'+idSelectFilter+' option').each(function(){
          if($(this).attr('value').indexOf(valuefilter)==0 || $(this).attr('value') == '-oth-'){
            $(this).clone().appendTo('#'+idNewSelectFilter);
          }
        });
      });

      $("#"+idNewSelectFilter).change(function(){
        $('#'+idSelectFilter).val($(this).val());
        $('#'+idSelectFilter).trigger('change');
      });

      if($("#"+idSelectFiltering).val()!=''){
        var valuefilter=$("#"+idSelectFiltering).val();
        $('#'+idSelectFilter+' option').each(function(){
            console.warn($(this).attr('value'));
          if($(this).attr('value').indexOf(valuefilter)==0 || $(this).attr('value') == '-oth-'){
            $(this).clone().appendTo('#'+idNewSelectFilter);
          }
        });
        if($("#"+idSelectFilter).val()!=''){
           $('#'+idNewSelectFilter).val($("#"+idSelectFilter).val());
        }
      }
    }
  });
}

/**
 * Function to filter a dual scale second dropdown by 1st
 * @version 1.0.0
 * @param {integer} qID : the number of dual scale question
 */
function selectFilter_selectFilterDualScale(qID){
  $(document).ready(function(){
    if($("#question"+qID).hasClass('array-flexible-duel-scale')){
      // Fix width of columns
      answertextwidth=$(this).find("col.answertext").attr('width').replace("%","");
      $(this).find("col.ddarrayseparator").attr('width',"2%");
      ddarrayseparatorwidth=$(this).find("col.ddarrayseparator").attr('width').replace("%","");
      var newwidth=(100-answertextwidth*1-ddarrayseparatorwidth*1)/2;
      $(this).find("col.dsheader").attr('width',newwidth+'%');
      $("#question"+qID+" table.question tbody tr").each(function(index){
        $(this).find("select").each(function(){
          //$(this).attr('id',$(this).attr('id').replace('#',"_"));
        });
        var idSelectFiltering = selectFilter_jqSelector($(this).find("select").eq(0).attr('id'));
        var idSelectFilter = selectFilter_jqSelector($(this).find("select").eq(1).attr('id'));
        var idNewSelectFilter = selectFilter_jqSelector('select'+qID+'_'+index);
        var NewSelectElement = "<select id='"+idNewSelectFilter+"'><option value=''>"+$("#"+idSelectFilter+" option[value='']:first").text()+"</option></select>";
        $("#"+idSelectFilter).hide();
        $("#"+idSelectFilter).after(NewSelectElement);
        $("#"+idNewSelectFilter).width($("#"+idSelectFilter).width());

        $("#"+idSelectFiltering).change(function(){
          $("#"+idSelectFilter).val('');
          $('#'+idNewSelectFilter).val('');
          var valuefilter=$(this).val().substring(0, $(this).val().length - 2);
          $('#'+idNewSelectFilter+' option').not(':first').remove();
          if($(this).val()==""){
            $('#'+idNewSelectFilter).hide();
          }else{
            $('#'+idNewSelectFilter).show();
            $("#"+idSelectFilter).find('option').each(function(){
              if($(this).attr('value').substring(0, $(this).attr('value').length - 2)==valuefilter || $(this).attr('value') == '-oth-'){
                $(this).clone().appendTo('#'+idNewSelectFilter);
              }
            });
          }

        });
        $("#"+idNewSelectFilter).change(function(){
          $('#'+idSelectFilter).val($(this).val());
          saveval=$('#'+idSelectFiltering).val();
          $('#'+idSelectFilter).trigger('change');
          if($(this).val()==""){
            $('#'+idSelectFiltering).val(saveval);
            $('#'+idSelectFiltering).trigger('change');
            $('#'+idSelectFilter).val($(this).val(""));
          }
        });

        if($("#"+idSelectFiltering).val()!=''){
          var valuefilter=$("#"+idSelectFiltering).val().substring(0, $("#"+idSelectFiltering).val().length - 2);
          $('#'+idSelectFilter+' option').each(function(){
            if($(this).attr('value').substring(0, $(this).attr('value').length - 2)==valuefilter || $(this).attr('value') == '-oth-'){
              $(this).clone().appendTo('#'+idNewSelectFilter);
            }
          });

          if($("#"+idSelectFilter).val()!=''){
             $('#'+idNewSelectFilter).val($("#"+idSelectFilter).val());
          }
        }else{
           $('#'+idNewSelectFilter).hide();
        }
      });
    }
  });
}
function selectFilter_jqSelector(str)
{
    return str.replace(/([;&,\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, '\\$1');
}
