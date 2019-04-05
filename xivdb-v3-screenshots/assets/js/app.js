import Settings from './Settings';
import Instance from './Instance';
import List from './List';

// grab all instances
$(`.${Settings.INSTANCE_CLASS_NAME}`).each((i, dom) => {
    const id = $(dom).attr('id');
    Settings.MS_SS_INSTANCES[id] = new Instance(id, dom);
});

// grab all instances
$(`.${Settings.LIST_CLASS_NAME}`).each((i, dom) => {
    const id = $(dom).attr('id');
    Settings.MS_SS_LISTS[id] = new List(id, dom);
});
