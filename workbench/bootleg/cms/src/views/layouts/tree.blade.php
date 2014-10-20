<div class="col-sm-3 col-md-offset-2 col-md-2 text-center treeOptions">
    @if($content_mode === 'template')
    <a href="{{URL::action('TemplateController@anyCreate')}}" class="btn btn-primary btn-sm create"><span class="glyphicon glyphicon-plus"></span> Create Template</a>
    @else
    <a href="{{URL::action('ContentsController@anyCreate')}}" class="btn btn-primary btn-sm create"><span class="glyphicon glyphicon-plus"></span> Create Content</a>
    @endif
</div>

<script>
    $(function() {
        $('.treeOptions .create').click(function(e){
            e.preventDefault();
            var tree = $(".tree").jstree(true);
            var parent = $('.tree').jstree('get_selected');
            var newNode = { state: "open", data: "New node!" };
            $('.tree').jstree("create_node", parent, newNode, 'last', function(e){
                tree.edit(e);
            });  
        });
    });
</script>

<div class="col-sm-3 col-md-offset-2 col-md-2 sidebar treeContainer">
    @include('cms::contents.tree', array('content'=>@$content, 'tree'=>@$tree))
</div>

<div class="col-sm-offset-3 col-md-offset-4 col-md-8">
    {{$cont}}
</div>
