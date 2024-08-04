"use client";

import React from "react";
import Table from "@/components/Table";
import useSWR from "swr";
import { get, post } from "@/lib/backendApi";
import {
  Alert,
  CircularProgress,
  Fab,
  IconButton,
  Tooltip,
} from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import EditNoteIcon from "@mui/icons-material/EditNote";
import DriveFileMoveIcon from "@mui/icons-material/DriveFileMove";
import { Data, FilterType, HeadCell, Order } from "@/components/Table/Table";
import { formatDateToHumanReadable } from "@/utils/formatting";
import InProgress from "@/components/InProgress";

enum MigrationConfigurationStatus {
  ACTIVE = 0,
  INACTIVE = 1,
}
interface MigrationConfigurationViewProps {
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

const fetcher = (url: string) => get(url);

function MigrationConfigurationView({
  render,
  setRender,
}: MigrationConfigurationViewProps) {
  const [order, setOrder] = React.useState<Order>("desc");
  const [orderBy, setOrderBy] = React.useState<keyof Data>("id");
  const [selected, setSelected] = React.useState<readonly number[]>([]);
  const [selectedType, setSelectedType] = React.useState<"remove" | "keep">(
    "remove"
  );
  const [filters, setFilters] = React.useState<
    Array<{ id: keyof Data; type: FilterType; value: string }>
  >([]);
  const [page, setPage] = React.useState(0);
  const [rowsPerPage, setRowsPerPage] = React.useState(5);

  const [onMigrating, setOnMigrating] = React.useState(false);
  const [onMigratingError, setOnMigratingError] = React.useState(false);
  const [onMigratingSuccess, setOnMigratingSuccess] = React.useState(false);

  let filterParams = "";

  filters.forEach((filter, index) => {
    filterParams += `&filters[${index}][key]=${encodeURIComponent(filter.id)}`;
    filterParams += `&filters[${index}][type]=${encodeURIComponent(
      filter.type
    )}`;
    filterParams += `&filters[${index}][value]=${encodeURIComponent(
      filter.value
    )}`;
  });

  const { data, error, isLoading, mutate } = useSWR(
    `/migration-configurations?page=${
      page + 1
    }&pagination=${rowsPerPage}&sort_by=${encodeURIComponent(
      orderBy
    )}&sort_order=${order}${filterParams}`,
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
      id: "total_migrations",
      isOrderable: true,
      isFilterable: true,
      label: "Total Migrations",
    },
    {
      id: "last_migration_at_column",
      isOrderable: true,
      isFilterable: true,
      label: "Last Migration",
    },
    {
      id: "created_at_column",
      isOrderable: true,
      isFilterable: true,
      label: "Created At",
    },
    {
      id: "status_column",
      isOrderable: true,
      isFilterable: true,
      label: "Status",
    },
    {
      id: "make_migration",
      isOrderable: false,
      isFilterable: false,
      label: "",
    },
    {
      id: "edit",
      isOrderable: false,
      isFilterable: false,
      label: "",
    },
  ];

  if (!isLoading && !error && data?.data) {
    data.data.data = data.data.data.map((d: any) => {
      return {
        ...d,
        last_migration_at_column:
          d.last_migration_at === null ? "No Migrations" : d.last_migration_at,
        created_at_column: formatDateToHumanReadable(d.created_at),
        status_column: MigrationConfigurationStatus[d.status],
        make_migration: (
          <div
            style={{
              display: "flex",
              justifyContent: "center",
              alignItems: "center",
            }}
            onClick={(event) => {
              event.stopPropagation();
            }}
          >
            <Tooltip title="Make Migration" placement="right-start">
              <IconButton
                aria-label="view"
                onClick={() => {
                  setOnMigrating(true);
                  post(
                    "/migration-configurations/make-migration/" + d.id,
                    {}
                  ).then((res) => {
                    if (res.status === 200) {
                      setOnMigratingSuccess(true);
                    } else {
                      setOnMigratingError(true);
                    }
                    setTimeout(() => {
                      setOnMigrating(false);
                      setOnMigratingError(false);
                      setOnMigratingSuccess(false);
                      mutate();
                    }, 2000);
                  });
                }}
              >
                <DriveFileMoveIcon fontSize="inherit" />
              </IconButton>
            </Tooltip>
          </div>
        ),
        edit: (
          <div
            style={{
              display: "flex",
              justifyContent: "center",
              alignItems: "center",
            }}
            onClick={(event) => {
              event.stopPropagation();
            }}
          >
            <Tooltip title="Edit" placement="right-start">
              <IconButton
                aria-label="edit"
                onClick={() => {
                  const url = new URL(window.location.href);
                  url.searchParams.set("option", "edit");
                  url.searchParams.set("id", d.id);
                  window.history.pushState({}, "", url);
                  setRender(!render);
                }}
              >
                <EditNoteIcon fontSize="inherit" />
              </IconButton>
            </Tooltip>
          </div>
        ),
      };
    });
  }

  const onDeleted = async (
    selected: readonly number[],
    selectedType: "remove" | "keep"
  ) => {
    if (selectedType === "remove") {
      await post("/migration-configurations/delete-multiple", {
        ids: selected,
      });
    } else {
      await post("/migration-configurations/delete-all-except", {
        ids: selected,
      });
    }
    mutate();
    setSelected([]);
    setSelectedType("remove");
  };

  return (
    <>
      {onMigrating && (
        <InProgress
          title="Making Migration"
          success={onMigratingSuccess}
          error={onMigratingError}
        />
      )}
      {!isLoading && !error && data?.data ? (
        <div
          style={{
            width: "84vw",
            display: "flex",
            flexDirection: "column",
            alignItems: "end",
          }}
        >
          <Table
            title="Migration Configurations"
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
            onDeleted={onDeleted}
          />
          <div
            style={{
              margin: "10px",
            }}
          >
            <Tooltip title="Add">
              <Fab
                color="primary"
                aria-label="add"
                onClick={() => {
                  const url = new URL(window.location.href);
                  url.searchParams.set("option", "create");
                  window.history.pushState({}, "", url);
                  setRender(!render);
                }}
              >
                <AddIcon />
              </Fab>
            </Tooltip>
          </div>
        </div>
      ) : isLoading ? (
        <div
          style={{
            width: "84vw",
            height: "60vh",
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
          }}
        >
          <CircularProgress />
        </div>
      ) : (
        <div
          style={{
            position: "fixed",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            width: "300px",
            top: "10%",
            left: "50%",
            zIndex: 1,
          }}
        >
          <Alert severity="error"> Error fetching data </Alert>
        </div>
      )}
    </>
  );
}

export default MigrationConfigurationView;
