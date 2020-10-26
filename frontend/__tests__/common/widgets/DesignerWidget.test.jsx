import React from 'react';
import { createSerializer } from 'enzyme-to-json';
import { mount, configure } from 'enzyme';
import Adapter from 'enzyme-adapter-react-16';
import { reducer as formReducer } from 'redux-form';
import { createStore, combineReducers } from 'redux';
import { Provider, connect } from 'react-redux';
import DesignerWidget from '../../../src/common/widgets/DesignerWidget';

window.yii = {
  getCsrfToken: () => true,
};

// Set mock current date
Date.now = jest.fn(() => 1516854226000);

// Configuring Enzyme
configure({ adapter: new Adapter() });
expect.addSnapshotSerializer(createSerializer({ mode: 'deep' }));

const generateStore = () => createStore(combineReducers({ form: formReducer }));

describe('Testing rendering DesignerWidget', () => {
  const emptyInitialValues = {
    Designer: {
      designerType: '0',
      licenseNumber: '',
      designerCategory: '',
      fullName: '',
      organizationName: '',
      iin: '',
      bin: '',
    },
  };
  const emptyDesignerProps = {
    checkUrl: '/ru/datamining/default/check-license',
    requestId: 1,
    bin: '',
    licenseNumber: '',
    iin: '',
    designerType: '0',
    updateDesigner: jest.fn(() => true),
    nextPage: jest.fn(() => true),
    previousPage: jest.fn(() => true),
  };
  const EmptyComponent = connect(() => ({ initialValues: emptyInitialValues }))(DesignerWidget);
  const emptyDesigner = mount(<Provider store={generateStore()}><EmptyComponent {...emptyDesignerProps} /></Provider>);
  it('should render with empty inputs', () => {
    expect(emptyDesigner.render()).toMatchSnapshot();
  });

  it('should render with errors in inputs', () => {
    emptyDesigner.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(emptyDesignerProps.updateDesigner.mock.calls.length).toBe(0);
    expect(emptyDesigner.render()).toMatchSnapshot();
  });

  const legalInitialValues = {
    Designer: {
      designerType: '1',
      licenseNumber: '',
      designerCategory: '',
      fullName: '',
      organizationName: '',
      iin: '',
      bin: '',
    },
  };
  const legalDesignerProps = {
    ...emptyDesignerProps,
    designerType: '1',
  };
  const LegalComponent = connect(() => ({ initialValues: legalInitialValues }))(DesignerWidget);
  const legalDesigner = mount(<Provider store={generateStore()}><LegalComponent {...legalDesignerProps} /></Provider>);
  it('should render with legal inputs', () => {
    expect(legalDesigner.render()).toMatchSnapshot();
  });
  it('should render legal with errors in inputs', () => {
    legalDesigner.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(legalDesignerProps.updateDesigner.mock.calls.length).toBe(0);
    expect(legalDesigner.render()).toMatchSnapshot();
  });

  const validInitialValues = {
    Designer: {
      designerType: '0',
      licenseNumber: '1',
      designerCategory: 'secondCategory',
      fullName: '1',
      organizationName: '1',
      iin: '050140004440',
      bin: '050140004440',
    },
  };
  const validDesignerProps = {
    ...emptyDesignerProps,
    bin: '050140004440',
    licenseNumber: '1',
    iin: '050140004440',
    designerType: '0',
  };
  const ValidComponent = connect(() => ({ initialValues: validInitialValues }))(DesignerWidget);
  const validDesigner = mount(<Provider store={generateStore()}><ValidComponent {...validDesignerProps} /></Provider>);
  it('should render with valid inputs', () => {
    expect(validDesigner.render()).toMatchSnapshot();
  });
  it('should invoke nextPage function', () => {
    validDesigner.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(validDesigner.find('.has-error').length).toBe(0);
    expect(validDesignerProps.updateDesigner.mock.calls.length).toBe(1);
    expect(validDesignerProps.nextPage.mock.calls.length).toBe(1);
    expect(validDesigner.render()).toMatchSnapshot();
  });
});
