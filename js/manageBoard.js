var thisAccount = sessionStorage.getItem( "Helen-account" );
var boardList = sessionStorage.getItem( "Helen-boards" );

$( document ).ready( async function()
{
    barInitial();
    // await new Promise( ( resolve, reject ) => initial( resolve, reject ) );

    $( ".glyphicon-trash" ).parent().click()
});

async function initial( res, rej )
{
    await new Promise( ( resolve, reject ) => checkPermission( resolve, reject ) );
    await new Promise( ( resolve, reject ) => manageBoard( resolve, reject ) );
}

function manageBoard( resolve, reject )
{
    let cmd = {};
    cmd[ "act" ] = "showBoardList";

    $.post( "../index.php", cmd, function( dataDB )
    {
        dataDB = JSON.parse( dataDB );

        if( dataDB.status == false )
        {
            swal({
                title: "錯誤",
                type: "error",
                text: data.errorCode,

            }).then(( result ) => {}, ( dismiss ) => {});
        }
        else
        {
            
        }
    });
}

function checkPermission( resolve, reject )
{
    if( !thisAccount )
    {
        swal({
            title: "載入頁面失敗",
            type: "error",
            text: "您沒有權限瀏覽此頁面",
            
        }).then(( result ) => {}, ( dismiss ) => {
            if ( dismiss )
            {
                $( "body" ).empty();
                let httpStatus = "<h1 style='font-weight: bolder; font-family: Times, serif;'>403 Forbidden</h1>";
                $( "body" ).append( httpStatus );
            }
        });

        resolve(0);

        return;
    }

    let cmd = {};
    cmd[ "act" ] = "showAuthority";
    cmd[ "account" ] = thisAccount;

    $.post( "../index.php", cmd, function( dataDB )
    {
        dataDB = JSON.parse( dataDB );

        if( dataDB.status == false )
        {
            swal({
                title: "載入頁面失敗",
                type: "error",
                text: "dataDB.errorCode",
    
            }).then(( result ) => {}, ( dismiss ) => {
                if ( dismiss )
                {
                    $( "body" ).empty();
                    let httpStatus = "<h1 style='font-weight: bolder; font-family: Times, serif;'>403 Forbidden</h1>";
                    $( "body" ).append( httpStatus );
                }
            });
            
            resolve(0);
        }
        else if( dataDB.data.permission == undefined || dataDB.data.permission < 3 )
        {
            swal({
                title: "載入頁面失敗",
                type: "error",
                text: "您沒有權限瀏覽此頁面",

            }).then(( result ) => {}, ( dismiss ) => {
                if ( dismiss )
                {
                    $( "body" ).empty();
                    let httpStatus = "<h1 style='font-weight: bolder; font-family: Times, serif;'>403 Forbidden</h1>";
                    $( "body" ).append( httpStatus );
                }
            });
    
            resolve(0);
        }
    
        resolve(0);
    });
}