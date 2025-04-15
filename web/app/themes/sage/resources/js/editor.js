import domReady from '@wordpress/dom-ready';

domReady(() => {
  
  const cssVars = {
    '--PRIMARY_COLOR': import.meta.env.VITE_PRIMARY_COLOR,
    '--SECONDARY_COLOR': import.meta.env.VITE_SECONDARY_COLOR,
    '--BODY_BG_COLOR': import.meta.env.VITE_BODY_BG_COLOR,
    '--BODY_COLOR': import.meta.env.VITE_BODY_COLOR,
    '--ANCHORLINK_COLOR': import.meta.env.VITE_ANCHORLINK_COLOR,
    '--FONT_FAMILY': import.meta.env.VITE_FONT_FAMILY,
    '--MAX_WIDTH': import.meta.env.VITE_MAX_WIDTH,
    '--HT_CONTAINER_MAX_WIDTH': import.meta.env.VITE_HT_CONTAINER_MAX_WIDTH,
    '--HT_CONTAINER_LARGE_MAX_WIDTH': import.meta.env.VITE_HT_CONTAINER_LARGE_MAX_WIDTH,
    '--HT_CONTAINER_MEDIUM_MAX_WIDTH': import.meta.env.VITE_HT_CONTAINER_MEDIUM_MAX_WIDTH,
    '--HT_CONTAINER_SMALL_MAX_WIDTH': import.meta.env.VITE_HT_CONTAINER_SMALL_MAX_WIDTH,
  };

  Object.entries(cssVars).forEach(([key, value]) => {
    document.documentElement.style.setProperty(key, value);
  });
  
});
