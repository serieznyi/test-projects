package com.moscoding.practice6;

import com.moscoding.practice6.domain.entity.AccountType;
import com.moscoding.practice6.domain.repository.AccountTypeRepository;

import javax.validation.constraints.NotEmpty;
import java.beans.PropertyEditorSupport;
import java.util.Optional;

public class AccountTypeEditor extends PropertyEditorSupport {
    private AccountTypeRepository accountTypeRepository;

    public AccountTypeEditor(AccountTypeRepository accountTypeRepository) {
        super();

        this.accountTypeRepository = accountTypeRepository;
    }

    @Override
    public void setAsText(@NotEmpty String id) throws IllegalArgumentException {
        Optional<AccountType> accountType = accountTypeRepository.findById(Long.parseLong(id));

        this.setValue(accountType.orElseThrow(IllegalArgumentException::new));
    }
}
