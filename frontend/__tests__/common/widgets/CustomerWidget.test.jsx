import React from 'react';
import { createSerializer } from 'enzyme-to-json';
import { mount, configure } from 'enzyme';
import Adapter from 'enzyme-adapter-react-16';
import { reducer as formReducer } from 'redux-form';
import { createStore, combineReducers } from 'redux';
import { Provider, connect } from 'react-redux';
import CustomerWidget from '../../../src/common/widgets/CustomerWidget';

window.yii = {
  getCsrfToken: () => true,
};
window.advertisement = {
  contactNumber: '1',
  status: 'stepOne',
  lang: 'ru',
  allRequestId: 1,
};

// Set mock current date
Date.now = jest.fn(() => 1516854226000);

// Configuring Enzyme
configure({ adapter: new Adapter() });
expect.addSnapshotSerializer(createSerializer({ mode: 'deep' }));

const generateStore = () => createStore(combineReducers({ form: formReducer }));

describe('Testing rendering CustomerWidget', () => {
  const emptyInitialValues = {
    Customer: {
      lastName: '',
      firstName: '',
      middleName: '',
      iin: '',
      bin: '',
      address: '',
      phoneNumber: '',
      organizationName: '',
      isLegalPerson: '0',
    },
  };
  const customerInfoEmptyProps = {
    lastName: '',
    firstName: '',
    middleName: '',
    bin: '',
    iin: '',
    organizationName: '',
    homeUrl: '/ru/profile-page/admin',
    titleName: 'Заказчик',
    requestId: 1,
    titlePage: 'Данные заказчика',
    letterOfAttorney: {
      alias: 'Доверенность или документ, удостоверяющий полномочия',
      fileName: null,
      id: null,
      name: 'letterOfAttorney',
      scenario: 'pdfFormat',
      type: 'AccompanyingDocuments',
    },
    isLegalPerson: '0',
    handleDocumentChange: jest.fn(() => true),
    updateCustomer: jest.fn(() => true),
    nextPage: jest.fn(() => true),
    messages: {},
    applicant: {
      lastName: '1',
      firstName: '1',
      middleName: '1',
      iinBin: '1',
    },
    labels: {
      customer: {},
      app: {},
    },
  };
  const EmptyComponent = connect(() => ({ initialValues: emptyInitialValues }))(CustomerWidget);
  const emptyCustomer = mount(<Provider store={generateStore()}><EmptyComponent {...customerInfoEmptyProps} /></Provider>);
  it('should render with empty inputs', () => {
    expect(emptyCustomer.render()).toMatchSnapshot();
  });

  it('should render with errors in inputs', () => {
    emptyCustomer.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(customerInfoEmptyProps.updateCustomer.mock.calls.length).toBe(0);
    expect(emptyCustomer.render()).toMatchSnapshot();
  });

  const anotherCustomerInitialValues = {
    Customer: {
      lastName: 'Тест',
      firstName: 'Тест',
      middleName: 'Тест',
      iin: '050140004440',
      bin: '050140004440',
      address: 'Тест',
      phoneNumber: '7777777777',
      organizationName: 'Тест',
      isLegalPerson: '0',
    },
  };
  const anotherCustomerProps = {
    ...customerInfoEmptyProps,
    lastName: 'Тест',
    firstName: 'Тест',
    middleName: 'Тест',
    bin: '050140004440',
    iin: '050140004440',
    organizationName: 'Тест',
  };
  const AnotherCustomerComponent = connect(() => ({ initialValues: anotherCustomerInitialValues }))(CustomerWidget);
  const anotherCustomer = mount(<Provider store={generateStore()}><AnotherCustomerComponent {...anotherCustomerProps} /></Provider>);
  it('should render with anotherCustomer error', () => {
    anotherCustomer.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(anotherCustomer.find('.has-error').length).toBe(0);
    expect(anotherCustomerProps.updateCustomer.mock.calls.length).toBe(0);
    expect(anotherCustomer.render()).toMatchSnapshot();
  });

  const validCustomerProps = {
    ...anotherCustomerProps,
    letterOfAttorney: {
      alias: 'Доверенность или документ, удостоверяющий полномочия',
      fileName: 'test.pdf',
      id: 1,
      name: 'letterOfAttorney',
      scenario: 'pdfFormat',
      type: 'AccompanyingDocuments',
    },
  };
  const validCustomer = mount(<Provider store={generateStore()}><AnotherCustomerComponent {...validCustomerProps} /></Provider>);
  it('should invoke handleCustomer', () => {
    validCustomer.find('.col-md-offset-10 > .btn-next').simulate('click');
    expect(validCustomer.find('.has-error').length).toBe(0);
    expect(validCustomerProps.updateCustomer.mock.calls.length).toBe(1);
    expect(validCustomer.render()).toMatchSnapshot();
  });
});
