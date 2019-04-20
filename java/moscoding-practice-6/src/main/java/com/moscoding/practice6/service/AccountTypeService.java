package com.moscoding.practice6.service;

import com.moscoding.practice6.domain.entity.AccountType;
import com.moscoding.practice6.domain.entity.Bank;
import com.moscoding.practice6.domain.repository.AccountTypeRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import javax.transaction.Transactional;

@Service
public class AccountTypeService {
    private AccountTypeRepository accountTypeRepository;

    @Autowired
    public void setAccountTypeRepository(AccountTypeRepository accountTypeRepository) {
        this.accountTypeRepository = accountTypeRepository;
    }

    @Transactional(value = Transactional.TxType.REQUIRED)
    public void createDefaultTypesForBank(Bank bank) {
        AccountType accountTypeCredit = new AccountType();
        accountTypeCredit.setType(AccountType.Type.CREDIT);
        accountTypeCredit.setBank(bank);
        accountTypeRepository.save(accountTypeCredit);

        AccountType accountTypeDebit = new AccountType();
        accountTypeDebit.setType(AccountType.Type.DEBIT);
        accountTypeDebit.setBank(bank);
        accountTypeRepository.save(accountTypeDebit);
    }
}
