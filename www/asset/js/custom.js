jQuery(document).ready(function() {
    dynamicForm($('ul.products'),'Add Product');
    dynamicForm($('ul.emis'),'Add EMI');
});

function addForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

function dynamicForm($collectionHolder, $label) {
    var $addTagLink = $('<a href="#" class="btn btn-info">'+$label+'</a>');
    var $newLinkLi = $('<li class="col-xs-12"></li>').append($addTagLink);
    
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $addTagLink.on('click', function(e) {
        e.preventDefault();
        addForm($collectionHolder, $newLinkLi);
    });
}
$(".delete").click(function(){
    if (!confirm("Do you want to delete?")){
      return false;
    }
});