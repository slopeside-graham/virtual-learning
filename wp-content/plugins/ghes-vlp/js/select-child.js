function setChildID(clicked_item) {
    setCookie("VLPSelectedChild", clicked_item.dataset.childId, 0, '/');
}