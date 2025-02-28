// assets/react/components/Tour.jsx
import React, { useState } from 'react';
import Joyride from 'react-joyride';

export default function Tour() {
  const [runTour, setRunTour] = useState(false);

  const steps = [
    {
      target: '.step-1',
      content: 'Ceci est la première étape !',
      disableBeacon: true,
    },
    {
      target: '.step-2',
      content: 'Ceci est la deuxième étape !',
    }
  ];

  return (
    <div>
      <button onClick={() => setRunTour(true)}>Démarrer le Tour</button>
      
      <Joyride
        steps={steps}
        run={runTour}
        continuous={true}
        showProgress={true}
        styles={{
          options: {
            zIndex: 10000, // Assure la visibilité
          }
        }}
      />
    </div>
  );
}