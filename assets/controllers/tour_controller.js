// assets/controllers/tour_controller.js
import { Controller } from '@hotwired/stimulus';
import Shepherd from 'shepherd.js';

export default class extends Controller {
    static values = { steps: Array }

    connect() {
        import('shepherd.js/dist/css/shepherd.css');
    }

    start() {
        const tour = new Shepherd.Tour({
            defaultStepOptions: {
                classes: 'shadow-md bg-purple-dark',
                scrollTo: { behavior: 'smooth', block: 'center' }
            }
        });

        // Configuration des étapes depuis le HTML
        this.stepsValue.forEach(stepConfig => {
            tour.addStep({
                title: stepConfig.title,
                text: stepConfig.text,
                attachTo: stepConfig.attachTo,
                buttons: [
                    { 
                        text: 'Suivant', 
                        action: tour.next,
                        classes: 'bg-blue-500 text-white px-4 py-2 rounded'
                    }
                ]
            });
        });

        tour.start();
    }
}