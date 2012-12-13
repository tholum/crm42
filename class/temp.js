YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.example.XPath = function() {
        var myColumnDefs = [
            {key:"type"},
            {key:"rank"},
            {key:"subnameatt"},
            {key:"age"},
            {key:"name"}
        ];

        var myDataSource = new YAHOO.util.DataSource("assets/php/xml_proxy.php");
        myDataSource.responseType = YAHOO.util.DataSource.TYPE_XML;
        myDataSource.useXPath = true;
        myDataSource.responseSchema = {
            metaFields: {rootatt:"/myroot/@rootatt", topnode:"//top", nestedatt:"//second/@nested"},
            resultNode: "item",
            fields: [{key:"type", locator:"@type"}, {key:"rank", parser:"number"}, "name", {key:"subnameatt", locator:"subitem/name/@type"}, {key:"age", locator:"subitem/age", parser:"number"}]
        };

        var myDataTable = new YAHOO.widget.DataTable("xpath", myColumnDefs, myDataSource);

        return {
            oDS: myDataSource,
            oDT: myDataTable
        };
    }();
});

