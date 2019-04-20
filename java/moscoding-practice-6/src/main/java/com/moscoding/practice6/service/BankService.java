package com.moscoding.practice6.service;

import com.moscoding.practice6.domain.entity.Bank;
import com.moscoding.practice6.domain.repository.AccountTypeRepository;
import com.moscoding.practice6.domain.repository.BankRepository;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.stereotype.Service;

import javax.transaction.Transactional;

@Service
@RequiredArgsConstructor
public class BankService {
    private final BankRepository bankRepository;
    private final AccountTypeService accountTypeService;
    private final ModelMapper modelMapper;

    @Transactional
    public Bank createNew(Bank form) {
        Bank bank = new Bank();

        modelMapper.map(form, bank);

        bankRepository.save(bank);

        accountTypeService.createDefaultTypesForBank(bank);

        return bank;
    }
}
