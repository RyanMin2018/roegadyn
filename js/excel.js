    function downloadExcel(targetId, SaveFileName) {
        var browser = navigator.userAgent.toLowerCase();
        // ie 구분
        if (-1 != browser.indexOf('trident')) {
            downloadExcelIe(targetId, SaveFileName);
        } else {
            var cssText = '.aaaa {font-size:11px; color:#333333; border:2px solid black; padding:10px 5px 8px 5px; background-color:#F3F5E0;}';
            $("#table_holiday").btechco_excelexport({
                containerid : targetId,
                datatype : $datatype.Table,
                cssStyle : cssText
            });
        }
    }
 
    function downloadExcelIe(targetId, SaveFileName) {
 
        // 방법 1이나 2 중 아무거나 되는거 쓰자
 
        // ********************** 
        // 방법 1 


        // 방법 1 

 
        // 스타일 변경 outline 스타일 가져와서 적용 가능
        /*
        var targetObj = document.getElementById(targetId);
        var outputCss = targetObj.currentStyle;
        targetObj.style.backgroundColor = outputCss.getAttribute('background-color');
        targetObj.style.borderStyle = outputCss.getAttribute('border-style');
        targetObj.style.border = outputCss.getAttribute('border');
         */
 
        // 스타일 변경 수동 적용 가능
        var cssText = '<style type="text/css">';
        cssText += '.aaaa {font-size:11px; color:#333333; border:2px solid black; padding:10px 5px 8px 5px; background-color:#F3F5E0;}';
        cssText += '</style>';
 
        var output = document.getElementById(targetId).outerHTML;
 
        if (!SaveFileName) {
            SaveFileName = 'FilteredReport.xls';
        }
 
        var oWin = window.open("about:blank", "_blank");
 
        oWin.document.write(cssText);
        oWin.document.write(output);
        oWin.document.close();
        // success = true, false
        var success = oWin.document.execCommand('SaveAs', false, SaveFileName);
        oWin.close();
 
        // ********************** 
        // 방법 2     


        // 방법 2   

        if (document.all.excelExportFrame == null) // 프레임이 없으면 만들자~!
        {
            var excelFrame = document.createElement("iframe");
            excelFrame.id = "excelExportFrame";
            excelFrame.name = "excelExportFrame";
            excelFrame.position = "absolute";
            excelFrame.style.zIndex = -1;
            excelFrame.style.visibility = "hidden";
            excelFrame.style.top = "-10px";
            excelFrame.style.left = "-10px";
            excelFrame.style.height = "0px";
            excelFrame.style.width = "0px";
            document.body.appendChild(excelFrame); // 아이프레임을 현재 문서에 쑤셔넣고..
        }
        var frmTarget = document.all.excelExportFrame.contentWindow.document; // 해당 아이프레임의 문서에 접근
 
        if (!SaveFileName) {
            SaveFileName = 'test.xls';
        }
 
        frmTarget.open("text/html", "replace");
        frmTarget.write('<html>');
        frmTarget
                .write('<meta http-equiv="Content-Type" content="application/vnd.ms-excel; charset=euc-kr"/>\r\n'); // 별로..
        frmTarget.write('<body onload="saveFile();">');
        frmTarget.write(document.getElementById(targetId).outerHTML); // tag를 포함한 데이터를 쑤셔넣고
        frmTarget.write('<script language="javascript">');
        frmTarget
                .write('function saveFile(){document.execCommand("SaveAs", true, "'
                        + SaveFileName + '");}');
        frmTarget.write('<\/script>');
        frmTarget.write('</body>');
        frmTarget.write('</html>');
        frmTarget.close();
        //frmTarget.charset="UTF-8"; // 자 코드셋을 원하는걸로 맞추시고..
        frmTarget.charset = "euc-kr";
        frmTarget.focus();
 
    }

