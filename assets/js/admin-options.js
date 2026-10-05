document.addEventListener('DOMContentLoaded', function () {
  var tabs = document.querySelectorAll('[data-storypress-tab]');
  var panels = document.querySelectorAll('[data-storypress-panel]');
  function selectTab(id) {
    tabs.forEach(function (tab) { var active = tab.dataset.storypressTab === id; tab.setAttribute('aria-selected', String(active)); tab.tabIndex = active ? 0 : -1; });
    panels.forEach(function (panel) { panel.hidden = panel.dataset.storypressPanel !== id; });
    if (window.history.replaceState) window.history.replaceState(null, '', '#' + id);
  }
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () { selectTab(tab.dataset.storypressTab); });
    tab.addEventListener('keydown', function (event) {
      var index = Array.prototype.indexOf.call(tabs, tab);
      if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') { event.preventDefault(); var next = event.key === 'ArrowRight' ? (index + 1) % tabs.length : (index - 1 + tabs.length) % tabs.length; tabs[next].focus(); selectTab(tabs[next].dataset.storypressTab); }
    });
  });
  var initial = window.location.hash.slice(1);
  if (!initial) {
    try { initial = window.sessionStorage.getItem('storypress-settings-tab') || ''; } catch (error) {}
  }
  if (initial && document.querySelector('[data-storypress-tab="' + initial + '"]')) selectTab(initial);

  var subTabs = document.querySelectorAll('[data-storypress-subtab]');
  var subPanels = document.querySelectorAll('[data-storypress-subpanel]');
  function selectSubTab(group, id) {
    subTabs.forEach(function (tab) {
      if (tab.dataset.storypressSubtabGroup !== group) return;
      var active = tab.dataset.storypressSubtab === id;
      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;
    });
    subPanels.forEach(function (panel) {
      if (panel.dataset.storypressSubtabGroup === group) panel.hidden = panel.dataset.storypressSubpanel !== id;
    });
  }
  subTabs.forEach(function (tab) {
    tab.addEventListener('click', function () { selectSubTab(tab.dataset.storypressSubtabGroup, tab.dataset.storypressSubtab); });
    tab.addEventListener('keydown', function (event) {
      if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') return;
      event.preventDefault();
      var groupTabs = document.querySelectorAll('[data-storypress-subtab-group="' + tab.dataset.storypressSubtabGroup + '"][data-storypress-subtab]');
      var index = Array.prototype.indexOf.call(groupTabs, tab);
      var next = event.key === 'ArrowRight' ? (index + 1) % groupTabs.length : (index - 1 + groupTabs.length) % groupTabs.length;
      groupTabs[next].focus();
      selectSubTab(tab.dataset.storypressSubtabGroup, groupTabs[next].dataset.storypressSubtab);
    });
  });

  var savedSubTabs = {};
  try { savedSubTabs = JSON.parse(window.sessionStorage.getItem('storypress-settings-subtabs') || '{}'); } catch (error) {}
  var activeTopTab = document.querySelector('[data-storypress-tab][aria-selected="true"]');
  if (activeTopTab && savedSubTabs[activeTopTab.dataset.storypressTab]) {
    selectSubTab(activeTopTab.dataset.storypressTab, savedSubTabs[activeTopTab.dataset.storypressTab]);
  }

  var settingsForm = document.querySelector('.storypress-settings__form');
  if (settingsForm) {
    settingsForm.addEventListener('submit', function (event) {
      if (settingsForm.dataset.saving === 'true') {
        event.preventDefault();
        return;
      }
      settingsForm.dataset.saving = 'true';

      var saveButton = settingsForm.querySelector('.storypress-save-button');
      if (saveButton) {
        var saveLabel = saveButton.querySelector('.storypress-save-button__label');
        var spinner = saveButton.querySelector('.storypress-save-spinner');
        saveButton.setAttribute('aria-busy', 'true');
        saveButton.disabled = true;
        saveButton.classList.add('is-loading');
        if (saveLabel) saveLabel.textContent = saveButton.dataset.savingLabel || 'Saving...';
        if (spinner) spinner.hidden = false;
      }

      var activeTab = document.querySelector('[data-storypress-tab][aria-selected="true"]');
      if (!activeTab) return;
      var activeSubTab = document.querySelector('[data-storypress-subtab-group="' + activeTab.dataset.storypressTab + '"][data-storypress-subtab][aria-selected="true"]');
      savedSubTabs[activeTab.dataset.storypressTab] = activeSubTab ? activeSubTab.dataset.storypressSubtab : '';
      try {
        window.sessionStorage.setItem('storypress-settings-tab', activeTab.dataset.storypressTab);
        window.sessionStorage.setItem('storypress-settings-subtabs', JSON.stringify(savedSubTabs));
      } catch (error) {}
    });
  }

  document.querySelectorAll('[data-logo-block]').forEach(function (block) {
    var logoButton = block.querySelector('[data-select-logo]');
    var logoRemove = block.querySelector('[data-remove-logo]');
    var logoInput = block.querySelector('[data-logo-input]');
    var logoPreview = block.querySelector('[data-logo-preview]');
    if (!logoButton || !logoRemove || !logoInput || !logoPreview) return;
    var frame = null;
    logoPreview.hidden = !logoPreview.children.length;
    function openPicker() {
      if (!window.wp || !window.wp.media) {
        if (window.console && window.console.warn) window.console.warn('WordPress media library is not available on this screen.');
        return;
      }
      if (frame) { frame.open(); return; }
      frame = window.wp.media({
        title: 'Select image',
        library: { type: 'image' },
        multiple: false,
        button: { text: 'Use image' }
      });
      frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        logoInput.value = String(attachment.id);
        var source = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
        logoPreview.innerHTML = '<img src="' + source + '" alt="">';
        logoPreview.hidden = false;
        logoRemove.hidden = false;
      });
      frame.open();
    }
    logoButton.addEventListener('click', function (event) {
      event.preventDefault();
      openPicker();
    });
    logoRemove.addEventListener('click', function (event) {
      event.preventDefault();
      logoInput.value = '';
      logoPreview.innerHTML = '';
      logoPreview.hidden = true;
      logoRemove.hidden = true;
    });
  });
});