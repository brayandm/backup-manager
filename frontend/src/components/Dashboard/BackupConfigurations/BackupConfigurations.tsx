"use client";

import React from "react";
import Table from "@/components/Table";
import useSWR from "swr";
import { get } from "@/lib/backendApi";
import { CircularProgress, Fab, IconButton, Tooltip } from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import EditNoteIcon from "@mui/icons-material/EditNote";

interface BackupConfigurationsProps {}

interface Data {
  id: number;
  [key: string]: any;
}
interface HeadCell {
  id: keyof Data;
  isOrderable: boolean;
  isFilterable: boolean;
  label: string;
}

const fetcher = (url: string) => get(url);

function BackupConfigurations({}: BackupConfigurationsProps) {
  const [order, setOrder] = React.useState<"asc" | "desc">("asc");
  const [orderBy, setOrderBy] = React.useState<keyof Data>("id");
  const [selected, setSelected] = React.useState<readonly number[]>([]);
  const [selectedType, setSelectedType] = React.useState<"remove" | "keep">(
    "remove"
  );
  const [filters, setFilters] = React.useState<
    Array<{ id: keyof Data; type: "like"; value: string }>
  >([]);
  const [page, setPage] = React.useState(0);
  const [rowsPerPage, setRowsPerPage] = React.useState(5);

  let filterParams = "";

  filters.forEach((filter, index) => {
    filterParams += `&filter[${index}][key]=${filter.id}`;
    filterParams += `&filter[${index}][type]=${filter.type}`;
    filterParams += `&filter[${index}][value]=${filter.value}`;
  });

  const { data, error, isLoading } = useSWR(
    `/backup-configurations?page=${
      page + 1
    }&pagination=${rowsPerPage}&sort_by=${orderBy}&sort_order=${order}&${filterParams}`,
    fetcher
  );

  const columns: readonly HeadCell[] = [
    {
      id: "id",
      isOrderable: true,
      isFilterable: true,
      label: "ID",
    },
    {
      id: "name",
      isOrderable: true,
      isFilterable: true,
      label: "Name",
    },
    {
      id: "created_at",
      isOrderable: true,
      isFilterable: true,
      label: "Created At",
    },
    {
      id: "total_backups",
      isOrderable: true,
      isFilterable: true,
      label: "Total Backups",
    },
    {
      id: "total_size",
      isOrderable: true,
      isFilterable: true,
      label: "Total Size",
    },
    {
      id: "last_backup",
      isOrderable: true,
      isFilterable: true,
      label: "Last Backup",
    },
    {
      id: "status",
      isOrderable: true,
      isFilterable: true,
      label: "Status",
    },
    {
      id: "edit",
      isOrderable: false,
      isFilterable: false,
      label: "",
    },
  ];

  if (data) {
    data.data.data = data.data.data.map((d: any) => {
      return {
        ...d,
        edit: (
          <div
            style={{
              display: "flex",
              justifyContent: "center",
              alignItems: "center",
            }}
            onClick={(event) => {
              event.stopPropagation();
              console.log("Edit Backup Configuration");
            }}
          >
            <Tooltip title="Edit" placement="right-start">
              <IconButton aria-label="edit">
                <EditNoteIcon fontSize="inherit" />
              </IconButton>
            </Tooltip>
          </div>
        ),
      };
    });
  }

  return data ? (
    <div
      style={{
        width: "86vw",
        display: "flex",
        flexDirection: "column",
        alignItems: "end",
      }}
    >
      <Table
        title="Backup Configurations"
        columns={columns}
        rows={data.data.data as Data[]}
        count={data.data.total}
        order={order}
        setOrder={setOrder}
        orderBy={orderBy}
        setOrderBy={setOrderBy}
        selected={selected}
        setSelected={setSelected}
        selectedType={selectedType}
        setSelectedType={setSelectedType}
        filters={filters}
        setFilters={setFilters}
        page={page}
        setPage={setPage}
        rowsPerPage={rowsPerPage}
        setRowsPerPage={setRowsPerPage}
      />
      <div
        style={{
          margin: "10px",
        }}
      >
        <Tooltip title="Add">
          <Fab color="primary" aria-label="add">
            <AddIcon />
          </Fab>
        </Tooltip>
      </div>
    </div>
  ) : isLoading ? (
    <div
      style={{
        width: "86vw",
        height: "60vh",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
      }}
    >
      <CircularProgress />
    </div>
  ) : (
    <></>
  );
}

export default BackupConfigurations;
