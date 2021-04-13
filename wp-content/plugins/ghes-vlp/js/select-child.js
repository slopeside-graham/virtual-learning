function setChildID(clicked_item) {
    setCookie("VLPSelectedChild", clicked_item.dataset.childId, 0, '/');
    setCookie("VLPAgeGroupId", clicked_item.dataset.childAgeGroup, 0, '/');
}