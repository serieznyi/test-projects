package com.moscoding.practice6.controller;

import com.moscoding.practice6.AccountTypeEditor;
import com.moscoding.practice6.FlashMessageHelper;
import com.moscoding.practice6.domain.entity.AccountType;
import com.moscoding.practice6.domain.entity.Client;
import com.moscoding.practice6.domain.repository.AccountTypeRepository;
import com.moscoding.practice6.domain.repository.ClientRepository;
import org.modelmapper.ModelMapper;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Sort;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.WebDataBinder;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import javax.validation.Valid;
import java.util.Optional;

import static com.moscoding.practice6.PagerHelper.DEFAULT_PAGE_SIZE;

@Controller
public class ClientController {
    private AccountTypeRepository accountTypeRepository;
    private ClientRepository clientRepository;
    private FlashMessageHelper flashMessageHelper;
    private ModelMapper modelMapper;

    ClientController(
            AccountTypeRepository accountTypeRepository,
            ClientRepository clientRepository,
            FlashMessageHelper flashMessageHelper,
            ModelMapper modelMapper
    ) {
        this.accountTypeRepository = accountTypeRepository;
        this.clientRepository = clientRepository;
        this.flashMessageHelper = flashMessageHelper;
        this.modelMapper = modelMapper;
    }


    @InitBinder
    public void initBinder(WebDataBinder binder) {
        binder.registerCustomEditor(AccountType.class, new AccountTypeEditor(accountTypeRepository));
    }

    @GetMapping(path = "/client/list")
    public String listShow(
            Model model,
            @RequestParam("page") Optional<Integer> pageNumber
    ) {
        int currentPage = pageNumber.orElse(1);

        Page<Client> page = clientRepository.findAll(
                PageRequest.of(
                        currentPage - 1,
                        DEFAULT_PAGE_SIZE,
                        new Sort(Sort.Direction.ASC, "name")
                )
        );

        model.addAttribute("page", page);

        return "page/client/list";
    }

    @GetMapping("/client/create")
    public String createShow(Model model) {
        model.addAttribute("form", new Client());
        model.addAttribute("account_type_list", accountTypeRepository.findAll());

        return "page/client/create";
    }

    @PostMapping("/client/create")
    public String createSubmit(
            @Valid @ModelAttribute("form") Client client,
            BindingResult bindingResult,
            Model model,
            RedirectAttributes redirectAttributes
    ) {

        if (bindingResult.hasErrors()) {
            model.addAttribute("account_type_list", accountTypeRepository.findAll());

            return "page/client/create";
        }

        clientRepository.save(client);

        flashMessageHelper.addSuccessMessage(redirectAttributes, "flash.entity.successful_created");

        return "redirect:/client/update/" + client.getId();
    }

    @GetMapping("/client/update/{id}")
    public String updateShow(
            @ModelAttribute("form") Client form,
            Model model,
            @PathVariable Long id
    ) {

        Optional<Client> clientFromDbOptional = clientRepository.findById(id);

        if (!clientFromDbOptional.isPresent()) {
            return "404";
        }

        modelMapper.map(clientFromDbOptional.get(), form);

        model.addAttribute("account_type_list", accountTypeRepository.findAll());
        model.addAttribute("form", form);
        model.addAttribute("id", id);

        return "page/client/update";
    }

    @PostMapping("/client/update/{id}")
    public String updateSubmit(
            @Valid @ModelAttribute("form") Client form,
            Model model,
            BindingResult bindingResult,
            @PathVariable Long id,
            RedirectAttributes redirectAttributes
    ) {
        if (bindingResult.hasErrors()) {
            model.addAttribute("form", form);
            model.addAttribute("id", id);
            model.addAttribute("account_type_list", accountTypeRepository.findAll());

            return "page/client/update";
        }

        if (!clientRepository.findById(id).isPresent()) {
            return "404";
        }

        this.clientRepository.save(form);

        flashMessageHelper.addSuccessMessage(redirectAttributes, "flash.entity.successful_updated");

        return "redirect:/client/update/" + form.getId();
    }

    @PostMapping("/client/delete/{id}")
    public String deleteSubmit(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        Optional<Client> clientOptional = clientRepository.findById(id);
        if (!clientOptional.isPresent()) {
            return "404";
        }

        clientRepository.delete(clientOptional.get());

        flashMessageHelper.addSuccessMessage(redirectAttributes, "flash.entity.successful_deleted");

        return "redirect:/client/list";
    }
}
