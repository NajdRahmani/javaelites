import { startStimulusApp } from '@symfony/stimulus-bridge';

// Enregistrez vos contrôleurs Stimulus ici
export const app = startStimulusApp(require.context(
    './controllers',
    true,
    /\.js$/
));