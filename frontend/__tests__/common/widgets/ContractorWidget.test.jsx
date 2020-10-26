import React from 'react';
import { createSerializer } from 'enzyme-to-json';
import { mount, configure } from 'enzyme';
import Adapter from 'enzyme-adapter-react-16';
import { reducer as formReducer } from 'redux-form';
import { createStore, combineReducers } from 'redux';
import { Provider, connect } from 'react-redux';
import ContractorWidget from '../../../src/common/widgets/ContractorWidget';

window.yii = {
  getCsrfToken: () => true,
};

// Set mock current date
Date.now = jest.fn(() => 1516854226000);

// Configuring Enzyme
configure({ adapter: new Adapter() });
expect.addSnapshotSerializer(createSerializer({ mode: 'deep' }));

const generateStore = () => createStore(combineReducers({ form: formReducer }));

describe('Testing ContractorWidget rendering', () => {
  const emptyInitialValues = {
    Contractor: {
      isLegalPerson: '0',
      lastName: '',
      firstName: '',
      middleName: '',
      address: '',
      organizationName: '',
      representativeName: '',
      workPosition: '',
      numberLicense: '',
      dateLicense: '',
      phoneNumber: '',
    },
    AcceptanceByOwners: {
      isOwner: false,
    },
  };
  const emptyContractorProps = {
    dateLicense: '',
    numberLicense: '',
    isLegalPerson: '0',
    isOwner: false,
    checkUrl: '/ru/datamining/default/check-license',
    requestId: 1,
    updateContractor: jest.fn(() => true),
    resetContractor: jest.fn(() => true),
    nextPage: jest.fn(() => true),
    previousPage: jest.fn(() => true),
  };
  const EmptyComponent = connect(() => ({ initialValues: emptyInitialValues }))(ContractorWidget);
  const emptyContractor = mount(<Provider store={generateStore()}><EmptyComponent {...emptyContractorProps} /></Provider>);
  it('should render with empty inputs', () => {
    expect(emptyContractor.render()).toMatchSnapshot();
  });
  it('should render with errors in inputs', () => {
    emptyContractor.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(emptyContractorProps.updateContractor.mock.calls.length).toBe(0);
    expect(emptyContractor.find('.has-error').length).toBe(6);
    expect(emptyContractor.render()).toMatchSnapshot();
  });

  const legalInitialValues = {
    Contractor: {
      ...emptyInitialValues.Contractor,
      isLegalPerson: '1',
    },
    AcceptanceByOwners: {
      isOwner: false,
    },
  };
  const legalContractorProps = {
    ...emptyContractorProps,
    isLegalPerson: '1',
  };
  const LegalComponent = connect(() => ({ initialValues: legalInitialValues }))(ContractorWidget);
  const legalContractor = mount(<Provider store={generateStore()}><LegalComponent {...legalContractorProps} /></Provider>);
  it('should render legal with empty inputs', () => {
    expect(legalContractor.render()).toMatchSnapshot();
  });
  it('should render legal with errors in inputs', () => {
    legalContractor.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(legalContractorProps.updateContractor.mock.calls.length).toBe(0);
    expect(legalContractor.find('.has-error').length).toBe(7);
    expect(legalContractor.render()).toMatchSnapshot();
  });

  const validInitialValues = {
    Contractor: {
      isLegalPerson: '0',
      lastName: 'test',
      firstName: 'test',
      middleName: 'test',
      address: 'test',
      organizationName: 'test',
      representativeName: 'test',
      workPosition: 'test',
      numberLicense: '11',
      dateLicense: '2018-03-13',
      phoneNumber: '7777777777',
    },
    AcceptanceByOwners: {
      isOwner: false,
    },
  };
  const validContractorProps = {
    ...emptyContractorProps,
    dateLicense: '2018-03-13',
    numberLicense: '11',
  };
  const ValidComponent = connect(() => ({ initialValues: validInitialValues }))(ContractorWidget);
  const validContractor = mount(<Provider store={generateStore()}><ValidComponent {...validContractorProps} /></Provider>);
  it('should render with valid inputs', () => {
    expect(validContractor.render()).toMatchSnapshot();
  });
  it('should invoke nextPage function', () => {
    validContractor.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(validContractor.find('.has-error').length).toBe(0);
    expect(validContractorProps.updateContractor.mock.calls.length).toBe(1);
    expect(validContractorProps.nextPage.mock.calls.length).toBe(1);
    expect(validContractor.render()).toMatchSnapshot();
  });
});
