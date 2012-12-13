function dom_killChild( parent , child ){
    document.getElementById(parent).removeChild( document.getElementById(child));
}
function dom_createChild( parent , child , type ){
    var new_child = document.createElement(type);
    new_child.id = child;
    document.getElementById(parent).appendChild( new_child );
    return new_child;
}

function dom_createChild_Document( child , type ){
    var new_child = document.createElement(type);
    new_child.id = child;
    document.getElementsByTagName("body")[0].appendChild( new_child );
    return new_child;
}
function dom_countSubObjects_1ifProperty( object , property){
        var tmpCt = 0;
        for( z in object ){
            tmpCt++;
        }
        if( object.hasOwnProperty( property ) ){
            tmpCt = 1;
        }
        return tmpCt;
}
function dom_countSubObjects( object ){
    var i = 0;
    for( x in object ){
        i++;
    }
    return i;
}


