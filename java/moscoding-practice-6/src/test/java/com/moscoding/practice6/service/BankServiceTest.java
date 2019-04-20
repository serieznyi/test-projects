package com.moscoding.practice6.service;

import com.moscoding.practice6.domain.entity.Bank;
import com.moscoding.practice6.domain.repository.BankRepository;
import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.junit.runner.RunWith;
import org.mockito.Mockito;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.test.context.junit4.SpringJUnit4ClassRunner;

import javax.annotation.Resource;

import static org.junit.jupiter.api.Assertions.*;

@RunWith(SpringJUnit4ClassRunner.class)
class BankServiceTest {
    private BankService bankService;

    @BeforeEach
    void setUp() {
        bankService = new BankService(
                Mockito.mock(BankRepository.class),
                Mockito.mock(AccountTypeService.class),
                new ModelMapper()
        );
    }

    @AfterEach
    void tearDown() {
    }

    @Test
    void createNew() {
        Bank bankForm = new Bank();
        bankForm.setName("Bank name 1");

        Bank bank = bankService.createNew(bankForm);

        assertNotNull(bank);
        assertEquals(bank.getName(), "Bank name 1");
    }
}