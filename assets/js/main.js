$(function(){ 

    $(document).on('submit', 'form', function(e){
        e.preventDefault();
        
        let form = this;
        
        $(form).find(".form-message").html("").removeClass("error");
        
        $(form).ajaxSubmit(function(data){
            
            let response = $.parseJSON(data);
            
            response.field.forEach(function(item){
                $(form).find("[name='"+item.name+"']").next(".form-message").html(item.message).addClass(item.type);
            });
            
            if(response.reset === true){
                form.reset();
            }
        }); 
    });
    

    let a=$('.masked-phone'), b=[];
    for(let c=0; c < a.length; c++){
        b.push(new PhoneField(a[c],a[c].dataset.phonemask, a[c].dataset.placeholder));
    }

    $("body").on('DOMNodeInserted', function(e) {
        let a=$(e.target).find(".masked-phone"), b=[];
        for(let c=0; c < a.length; c++){
            b.push(new PhoneField(a[c],a[c].dataset.phonemask, a[c].dataset.placeholder));
        }
    });
    
    
    $(document).on('click', '.contact-items .add-item', function(e){
        $(this).parents(".contact-items").parent().append($(this).parent().parent().clone());
        $(".contact-items").last().find("input#name").val("");
        $(".contact-items").last().find("input#email").val("");
        $(".contact-items").last().find("input#phone").val("+7 (___) ___-__-__");
        inputSerialise();
    });
    
    $(document).on('click', '.contact-items .remove-item', function(e){
        if($(".contact-items").length > 1){
            $(this).parent().parent().remove();
            inputSerialise();
        }
    });
    
    let inputSerialise = function(){
        $(".contact-items").each(function(i) {
            $(this).find("input").each(function() {
                $(this).attr("name", $(this).attr("name").replace(/[0-9]/g, i));
            });
        });
    }
    
    
    $(document).on('keyup', '[name="search"]', function(e){
        $("table").html("");
        $.get("/API/Contacts/getContacts?phone="+this.value, function(data){
            let items = $.parseJSON(data);
            let list = "";
            for(let i=0; i < items.length; i++) {
                list += '<tr>';
                list += '<th colspan="3"><b>Идентификтор:</b> '+items[i].source_id+'</th>';
                list += '</tr>';
                let contact_data = $.parseJSON(items[i].contact_data);
                for(let n=0; n < contact_data.length; n++) {
                    list += '<tr>';
                    list += '<td>Имя: '+contact_data[n].name+'</td>';
                    list += '<td>Почта: '+contact_data[n].email+'</td>';
                    list += '<td>Телефон: '+contact_data[n].phone+'</td>';
                    list += '</tr>';
                }
            }
            $("table").html(list);
        });
    });
    
});