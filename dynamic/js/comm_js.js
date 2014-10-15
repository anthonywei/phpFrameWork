
function addMoreList()
{   
    //ajax 获取
    page_index = Number(document.getElementById("current_page").value);
    page_index += 1;
    city_id = document.getElementById("city_select").value;
    search_name = document.getElementById("search_input").value;
    page_num = 10;

    //alert("http://121.199.45.110/dz/business/web/web_merchants.php?r=get_ajax_list&city="+city_id+"&page_index="+page_index+"&page_num=10&search_name="+search_name);
    htmlobj=$.ajax({url:"http://121.199.45.110/dz/business/web/web_merchants.php?r=get_ajax_list&city="+city_id+"&page_index="+page_index+"&page_num=10&search_name="+search_name,
                async:false});
           
    //alert(page_index);     
    //alert(htmlobj.responseText);
    
    document.getElementById("all_list").innerHTML = 
        document.getElementById("all_list").innerHTML + htmlobj.responseText;
        
    
    if(htmlobj.responseText != "")
        document.getElementById("current_page").value = page_index;
    
    //alert(document.getElementById("current_page").value);
}

function searchName()
{
    //直接跳转即可.
    city_id = document.getElementById("city_select").value;
    search_name = document.getElementById("search_input").value;
    
    url = "http://121.199.45.110/dz/business/web/web_merchants.php?r=get_merchants_list&city="+city_id+"&page_index=0&page_num=10&search_name="+search_name;
    //alert(url);
    window.location.href=url;
}

function changeCity()
{
    city_id = document.getElementById("city_select").value;
    //alert(city_id);
    search_name = document.getElementById("search_input").value;
    
    url = "http://121.199.45.110/dz/business/web/web_merchants.php?r=get_merchants_list&city="+city_id+"&page_index=0&page_num=10&search_name="+search_name;
    //alert(url);
    window.location.href=url;
}