// Import all necessary Storefront plugins and scss files
import PopupPlugin from './script/plugin/popup-plugin';

// Register them via the existing PluginManager
const PluginManager = window.PluginManager;
PluginManager.register('PopupPlugin', PopupPlugin, '.alpha-popup--wrapper');