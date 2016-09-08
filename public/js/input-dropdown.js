var InputDropDown = function (inputName, ulName) {

    this.data = [];
    this.liTemplate = '<li><a>%@</a></li>';
    this.inputName = inputName || inputName != '' ? inputName : 'idd-input';
    this.ulName = ulName || ulName != '' ? ulName : 'idd-ul';

    this.input = $('#' + this.inputName);
    this.btnGroup = this.input.closest('.btn-group');

    this.loading_gif = '<li style="text-align:center;"><img style="max-height:50px;" src="/images/loading_spinner.gif"></li>';

    this.setData = function(dataArray){

        if(dataArray == null) dataArray = [];

        if(dataArray.length > 0){
            if (!(dataArray instanceof jQuery)){
                dataArray = $(dataArray);
            }

            this.data = dataArray;

            $('#' + this.ulName).empty();

            var thisClass = this;
            dataArray.each(function(i){

                var element = dataArray[i];

                var li = $(thisClass.liTemplate.replace('%@', element.content));

                li.on('mousedown', function(event) {
                    event.preventDefault();
                }).click(function(e){

                    thisClass.listItemClicked(i,e);
                });
                $('#' + thisClass.ulName).append(li);

            });

            if($('#' + this.inputName).is(":focus"))
                this.btnGroup.addClass('open');
        }
        else{
            this.btnGroup.removeClass('open');
        }
    };

    var btnGroup = this.btnGroup;

    $('#' + this.inputName).focus(function(){

        if(this.data != null && this.data.length > 0) {
            this.btnGroup.addClass('open');
        }
    });

    this.showLoading = function () {
        $('#' + this.ulName).empty();
        $('#' + this.ulName).append(this.loading_gif);
        btnGroup.addClass('open');
    };

    this.hideLoading = function(){
        $('#' + this.ulName).empty();
    };

    this.getData = function(index){

        return this.data[index];
    };

    this.listItemClicked =  function(liIndex, e){ /*feel free to override this function in instance*/ }
    this.listItemClickedAction = function(liIndex){
        var thisClass = this;
        $('#' + thisClass.inputName).val(thisClass.data[liIndex].content);
        thisClass.btnGroup.removeClass('open');
    };

    this.deleteLiAtIndex = function(liIndex){
        var thisClass = this;
        var li = $('#' + thisClass.ulName + " li").eq( liIndex );
        li.remove();
    };

    $('#' + this.inputName).blur(function () {

        btnGroup.removeClass('open');
    });
};