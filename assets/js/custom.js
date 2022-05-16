function number_format(n,s) {
    return n.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, s);
}

// function defineTerminalID() {
//     var iPageTabID = sessionStorage.getItem("tabID");
//     // if it is the first time that this page is loaded
//     if (iPageTabID == null)
//     {
//         var iLocalTabID = localStorage.getItem("tabID");
//         // if tabID is not yet defined in localStorage it is initialized to 1
//         // else tabId counter is increment by 1
//         var iPageTabID = (iLocalTabID == null) ? 1 : Number(iLocalTabID) + 1
//         // new computed value are saved in localStorage and in sessionStorage
//         localStorage.setItem("tabID",iPageTabID);
//         sessionStorage.setItem("tabID",iPageTabID);
//         document.cookie = "terminal_id="+iPageTabID+";path=/";
//     }
//     else if(iPageTabID != document.cookie){
//         // windows session still exist and base on session to update value cookie
//         document.cookie = "terminal_id="+iPageTabID+";path=/";
//         localStorage.setItem("tabID",iPageTabID);
//     }
// }

// defineTerminalID();