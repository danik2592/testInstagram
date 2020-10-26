// eslint-disable-next-line import/no-extraneous-dependencies
import Redbox from 'redbox-react';
import React from 'react';
import ReactDOM from 'react-dom';
import PatchedReactDOM from '@hot-loader/react-dom';
import { AppContainer } from 'react-hot-loader';
import App from './components/App';

// combine everyhing

const rootEl = document.getElementById('react-app');

const render = (Component) => {
  PatchedReactDOM.render(
      <AppContainer >
          <Component />
      </AppContainer>,
      rootEl,
  );
};

// Hot Module Replacement API
if (module.hot) {
  render(App);
  // update components
  module.hot.accept('./components/App', () => {
    render(App);
  });
} else {
  render(App);
}

