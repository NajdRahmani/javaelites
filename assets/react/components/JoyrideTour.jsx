// assets/react/components/JoyrideTour.jsx
import { useState } from 'react';
import Joyride from 'react-joyride';

const JoyrideTour = () => {
  const [runTour, setRunTour] = useState(false);
  const [steps] = useState([
    {
      target: '#premier-element',
      content: 'Ceci est la première étape du guide',
      disableBeacon: true,
    },
    {
      target: '.second-element',
      content: 'Deuxième étape du tutoriel',
    }
  ]);

  return (
    <>
      <button onClick={() => setRunTour(true)}>Démarrer le guide</button>
      
      <Joyride
        steps={steps}
        run={runTour}
        continuous
        scrollToFirstStep
        showProgress
        showSkipButton
        styles={{
          options: {
            primaryColor: '#f04'
          }
        }}
      />
    </>
  );
};

export default JoyrideTour;