// assets/controllers/tour_controller.js
import { Controller } from '@hotwired/stimulus';
import Shepherd from 'shepherd.js';

export default class extends Controller {
  static values = { steps: Array };
  
  connect() {
    const tour = new Shepherd.Tour({
      defaultStepOptions: {
        classes: 'shepherd-theme-default',
        scrollTo: true
      }
    });

    this.stepsValue.forEach(step => {
      tour.addStep(step);
    });

    tour.start();
  }
}